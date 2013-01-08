<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
class Strava_model extends CI_Model {

	public function __contruct() {
		parent::__construct();
	}

	public function get_information($id) {
		$first_day = date("Y-m-01");
		$today = date("Y-m-d");

		//get members
		$request = "http://www.strava.com/api/v1/clubs/".$id."/members";
		$response = file_get_contents($request);
		$members = json_decode($response);
		$result = array();
		$elevation = array();
		if(!isset($members->error)) {
			//get rides
			foreach($members->members as $key => $member) {
				$user_name = $member->name;
				$user_id = $member->id;
				$offset = 0;
				$request = "http://www.strava.com/api/v1/rides?startDate=".$first_day."&endDate=".$today."&athleteId=".$user_id."&offset=".$offset;
				$response = file_get_contents($request);
				$rides = json_decode($response)->rides;

				$total_elevationGain = 0;
				$total_num_rides = 0;
				$num_rides = count($rides);
				while($num_rides > 0) {   // if users have more than 50 rides, need to keep searhing for the rides
					$total_num_rides += $num_rides;
					//get elevationGain per each ride					
					foreach($rides as $ride) {
						$ride_id = $ride->id;
						$request = "http://www.strava.com/api/v1/rides/".$ride_id;
						$response = file_get_contents($request);
						$ride_info = json_decode($response);
		
						$total_elevationGain += $ride_info->ride->elevationGain;   //accumulate elevationGain
					}
					$offset += 50;
					$request = "http://www.strava.com/api/v1/rides?startDate=".$first_day."&endDate=".$today."&athleteId=".$user_id."&offset=".$offset;
					$response = file_get_contents($request);
					$rides = json_decode($response)->rides;
					$num_rides = count($rides);
					
				}
				$result[] = array("name" => $user_name, "num_rides" => $total_num_rides, "total_elevationGain" => $total_elevationGain);
				$elevation[$key] = $total_elevationGain;
			}
		}

		//sort array on total_elevationGain in decending order
		array_multisort($elevation, SORT_DESC, $result);

		return $result;
	}
					
}
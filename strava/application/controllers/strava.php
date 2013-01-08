<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');


class Strava extends CI_Controller {
	
	public function __construct() {
		parent::__construct();
		$this->load->model("strava_model");
	}
	
	public function index() {
		$this->load->view("strava_view");
	}	
	
	public function get_information() {
		if($this->input->post("submit")) {
			$this->form_validation->set_rules("club_id", "CLUB ID", "required|trim|xss_clean|");
			
			if($this->form_validation->run()) {	
				//get club_id
				$club_id = $this->input->post("club_id");
				//get all members in the club
				$all_info = $this->strava_model->get_information($club_id);
				
				if(count($all_info) > 0) {
					// Make a html to display output
					$html = "<table border='1' cellspacing='0' cellpadding='0' align='center' width='500'>";
					$html .= "<tr align='center'>";
					$html .= "<td>Rank </td>";
					$html .= "<td>Name </td>";
					$html .= "<td>Total Evaluataion Gain</td>";
				    $html .= "<td>Number of Rides</td>";
				    $html .= "<td>Average Evalutation Gain per Ride</td>";
					$html .= "</tr>";
					foreach ($all_info as $key => $info) {
						$html .= "<tr align='center'>";
						$html .= "<td>".($key + 1)."</td>";
						$html .= "<td>".$info["name"]."</td>";
						$html .= "<td>".$info["total_elevationGain"]."</td>";
						$html .= "<td>".$info["num_rides"]."</td>";
						if($info["num_rides"] == 0) {
							$html .= "<td>".$info["total_elevationGain"]."</td>";
						} else {
							$html .= "<td>".($info["total_elevationGain"] / $info["num_rides"])."</td>";
						}
						$html .= "</tr>";
					}
					$html .="</table>";
					$data["info"] = $html;
					$this->load->view("strava_view", $data);
				} else {    // no information about the group
					$data["error"] = "no information about club ".$club_id;
					$this->load->view("strava_view", $data);
				}
				
			} else {
				$this->index();
			}
		} else {
			$this->index();
		}
	}
}
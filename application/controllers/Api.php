<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {
	public function info()
	{
		$this->load->view('test');
	}

	public function db_connect()
	{
		$this->load->database();
	}
	
	public function db_disconnect()
	{
		$this->db->close();
	}

	public function output_json_format($data)
	{
		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($data, JSON_UNESCAPED_UNICODE));
	}
	
	public function get_favorite_city_departures()
	{
	}
	
	public function get_city_departures()
	{
		$this->db_connect();

		$response_data = array();
		$query = $this->db->get_where('stop_info', array('division' => 001));

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('id' => $row->id, 'name' => $row->name));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
	}

	public function get_city_destinations($departure)
	{
	}

	public function get_intercity_destinations()
	{
		$this->db_connect();

		$response_data = array();
		$query = $this->db->get_where('stop_info', array('division !=' => 001));

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('id' => $row->id, 'name' => $row->name));
		}
		
		$this->output_json_format($response_data);

		$this->db_disconnect();
	}

	public function get_stops_in_route()
	{
	}
}

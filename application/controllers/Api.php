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
	
	public function get_favorite_departures()
	{
	}
	
	public function get_departures()
	{
		$this->db_connect();

		$response_data = array();

		//$query = $this->db->get_where('sido', array('id' => 1, 'name' => '서울'));
		$query = $this->db->get_where('sido', array());

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('id' => $row->id, 'name' => $row->name));
		}

		$this->output
			->set_content_type('application/json', 'utf-8')
			->set_output(json_encode($response_data, JSON_UNESCAPED_UNICODE));
		$this->db_disconnect();
	}

	public function get_favorite_destinations()
	{
	}

	public function get_destinations()
	{
	}

	public function get_search_results()
	{
	}

	public function get_time_table()
	{
	}
}

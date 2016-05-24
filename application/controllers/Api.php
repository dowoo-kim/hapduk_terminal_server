<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Api extends CI_Controller {

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
	
	public function get_city_favorite_departures()
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

	public function get_city_favorite_destinations()
	{
	}

	public function get_city_destinations($departure_id)
	{
	}

	public function get_intercity_destinations()
	{
		$this->db_connect();

		$response_data = array();
		
		$this->db->select(array('a.id as stop_info_id',
					'a.name as stop_info_name',
					'b.id as division_id',
					'b.name as division_name'));
		$this->db->from(array('stop_info as a',
					'division as b'));
		$this->db->where('a.division = b.id');
		$this->db->where('a.division != 001');

		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('stop_info_id' => $row->stop_info_id,
							'stop_info_name' => $row->stop_info_name,
							'division_id' => $row->division_id,
							'division_name' => $row->division_name));
		}
		
		$this->output_json_format($response_data);

		$this->db_disconnect();
	}

	public function get_intercity_destinations_to_division($division_id)
	{
		$this->db_connect();

		$response_data = array();

		$this->db->select(array('a.id as stop_info_id',
					'a.name as stop_info_name',
					'b.id as division_id',
					'b.name as division_name'));
		$this->db->from(array('stop_info as a',
					'division as b'));
		$this->db->where('a.division = b.id');
		$this->db->where('a.division =',$division_id);

		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('stop_info_id' => $row->stop_info_id,
							'stop_info_name' => $row->stop_info_name,
							'division_id' => $row->division_id,
							'division_name' => $row->division_name));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
	}
	
	public function get_intercity_favorite_destinations()
	{
	}

	public function get_route_id($departure_id, $destination_id)
	{
		$this->db_connection();

		$response_data = array();

		$this->db->select('id');
		$this->db->from('routes');
		$this->db->where('departure = ', $departure_id);
		$this->db->where('destination = ', $destination_id);

		$query = $this->db->get();

		foreach($query->result() as $row)
		{
			array_push($response_data, array('route_id' => $row->id));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
	}
	
	public function get_route($route_id)
	{
		$this->db_connect();
		
		$response_data = array();

		$this->db->select(array('a.id as route_id',
					'a.departure as departure_id',
					'b.name as departure_name',
					'a.destination as destination_id',
					'c.name as destination_name',
					'a.required as required'));
		$this->db->from(array('routes as a',
					'stop_info as b',
					'stop_info as c'));
		$this->db->where('a.departure = b.id');
		$this->db->where('a.destination = c.id');
		$this->db->where('a.id = ', $route_id);
		
		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('route_id' => $row->route_id,
							'departure_id' => $row->departure_id,
							'departure_name' => $row->departure_name,
							'destination_id' => $row->destination_id,
							'destination_name' => $row->destination_name,
							'required' => $row->required));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
	}

	public function get_stops_in_route($route_id)
	{
		$this->db_connect();

		$response_data = array();

		$this->db->select(array('a.route_id as route_id',
					'b.departure_time as departure_time',
					'c.id as stop_id',
					'c.name as stop_name',
					'a.sequence as stops_in_route_sequence'));
		$this->db->from(array('stops_in_route as a',
					'time_table as b',
					'stop_info as c'));
		$this->db->where('a.route_id = b.route_id');
		$this->db->where('a.stop_info_id = c.id');
		$this->db->where('a.route_id', $route_id);
		$this->db->order_by('b.departure_time','ASC');
		$this->db->order_by('a.sequence','ASC');
		
		$query = $this->db->get();

		foreach($query->result() as $row)
		{
			array_push($response_data, array('route_id' => $row->route_id,
							'departure_time' => $row->departure_time,
							'stop_id' => $row->stop_id,
							'stop_name' => $row->stop_name,
							'stops_in_route_sequence' => $row->stops_in_route_sequence));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
	}

	public function get_fee($stop_info_id)
	{
		$this->db_connect();

		$response_data = array();

		$this->db->select(array('id',
					'name',
					'child',
					'teenager',
					'adult'));
		$this->db->from('stop_info');
		$this->db->where('id', $stop_info_id);
		
		$query = $this->db->get();

		foreach($query->result() as $row)
		{
			array_push($response_data, array('stop_info_id' => $row->id,
							'stop_info_name' => $row->name,
							'child' => $row->child,
							'teenager' => $row->teenager,
							'adult' => $row->adult));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
	}
}

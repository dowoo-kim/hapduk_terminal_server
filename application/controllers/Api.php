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
		/*
		$this->db_connect();
		$response_data = array();
		
		$query = $this->db->get_where('stop_info', array('division' => 001));

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('id' => $row->id, 'name' => $row->name));
		}

		$this->output_json_format($response_data);

		$this->db_disconnect();
		*/
	}

	public function get_city_favorite_destinations()
	{
	}

	public function get_city_destinations()
	{
	}

	public function get_intercity_destinations()
	{
		$this->db_connect();

		$response_data = array();
		
		$this->db->select(array('a.id as destination_id',
					'a.name as destination_name',
					'b.id as division_id',
					'b.name as division_name'));
		$this->db->from(array('stop_info as a',
					'division as b'));
		$this->db->where('a.division not in (001,000)');
		$this->db->where('a.division = b.id');

		$query = $this->db->get();

		foreach ($query->result() as $row)
		{
			array_push($response_data, array('destinationId' => $row->destination_id,
							'destinationName' => $row->destination_name,
							'divisionId' => $row->division_id,
							'divisionName' => $row->division_name));
		}
		
		$this->output_json_format($response_data);

		$this->db_disconnect();
	}
	
	public function get_intercity_favorite_destinations()
	{
	}

	public function get_route($destination_id)
	{
		$this->db_connect();

		$route_ids = array();

		$this->db->select('id');
		$this->db->from('routes');
		$this->db->where('destination = ', $destination_id);
		
		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if(!in_array($row->id, $route_ids))
				{
					array_push($route_ids, $row->id);
				}
			}
		}
		
		$this->db->select('route_id');
		$this->db->from('stops_in_route');
		$this->db->where('stop_info_id = ', $destination_id);

		$query = $this->db->get();
		
		if($query->num_rows() > 0)
		{
			foreach($query->result() as $row)
			{
				if(!in_array($row->route_id, $route_ids))
				{
					array_push($route_ids, $row->route_id);
				}
			}
		}

		$this->db->select(array('b.route_id as route_id',
					'a.id as stops_in_route_id',
					'a.sequence as stops_in_route_sequence',
					'b.id as time_table_id',
					'b.departure_time as departure_time',
					'c.id as stop_info_id',
					'c.name as stop_info_name',
					'c.child as stop_info_child',
					'c.teenager as stop_info_teenager',
					'c.adult as stop_info_adult',
					'c.required as stop_info_required'));
		$this->db->from(array('stops_in_route as a',
					'time_table as b',
					'stop_info as c'));
		$this->db->where_in('b.route_id', $route_ids);
		$this->db->where('b.route_id = a.route_id');
		$this->db->where('a.stop_info_id = c.id');
		$this->db->order_by('b.departure_time', 'ASC');
		$this->db->order_by('b.route_id', 'ASC');
		$this->db->order_by('a.sequence', 'ASC');

		$query = $this->db->get();

		$stops_in_route = array();

		foreach ($query->result() as $row)
		{
			array_push($stops_in_route, array('id' => $row->stop_info_id,
							'name' => $row->stop_info_name,
							'route_id' => $row->route_id,
							'stops_in_route_id' => $row->stops_in_route_id,
							'sequence' => $row->stops_in_route_sequence,
							'time_table_id' => $row->time_table_id,
							'departure_time' => $row->departure_time,
							'child' => $row->stop_info_child,
							'teenager' => $row->stop_info_teenager,
							'adult' => $row->stop_info_adult,
							'required' => $row->stop_info_required));
		}

		$this->output_json_format($stops_in_route);

		$this->db_disconnect();
	}
}

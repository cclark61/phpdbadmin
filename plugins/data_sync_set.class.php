<?php

class data_sync_set extends database_interface_object
{	
	// Constructor function
	public function __construct()
	{
		$this->set_data_source($_SESSION['default_data_source'], "data_sync_sets");
		$this->set_pkey("id");
		$this->use_bind_params();
	}
}


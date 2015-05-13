<?php

class Home extends CI_Controller
{
	private $data;

	function __construct()
	{
		parent::__construct();

	}

	public function index()
	{
		echo "Home you are...";
	}
}
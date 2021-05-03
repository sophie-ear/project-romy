<?php

class UserForm extends Form{ 

	public function build(){

		$this->addFormField("firstName");
		$this->addFormField("lastName");
		$this->addFormField("day");
		$this->addFormField("month");
		$this->addFormField("year");
		$this->addFormField("address");
		$this->addFormField("zipcode");
		$this->addFormField("city");
		$this->addFormField("country");
		$this->addFormField("phone");
		$this->addFormField("email");
		$this->addFormField("password");
	}
}
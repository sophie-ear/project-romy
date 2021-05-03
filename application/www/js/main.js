'use strict'; 

var burgerShop = document.getElementById("burgerShop");
var shopNav = document.getElementById("shopNav");
var burgerNav = document.getElementById("burgerUser");
var userNav = document.getElementById("userNav");

function showShopNav(){
	shopNav.classList.toggle('downShop');
	if(userNav.classList.contains('downUser')){
		userNav.classList.toggle('downUser');
	}
}


function showUserNav(){
	userNav.classList.toggle('downUser');
	if(shopNav.classList.contains('downShop')){
		shopNav.classList.toggle('downShop');
	}
}


function start(){
	
	burgerShop.addEventListener('click', showShopNav);
	burgerNav.addEventListener('click', showUserNav);

}

document.addEventListener('DOMContentLoaded', start);

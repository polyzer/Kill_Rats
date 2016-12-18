<?php 
	session_start();
	$_SESSION["vk_cheeser"]["true_connection"] = "true";
?>

<!DOCTYPE html>
<html>
<head>
	<meta charset="UTF-8" />
	<title>Cheeser! Kill rats!</title>
	<script src="https://cdn.rawgit.com/konvajs/konva/1.0.2/konva.min.js"></script>
	<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>

	<script src="//vk.com/js/api/xd_connection.js?2"  type="text/javascript"></script>

	<link rel="stylesheet" type="text/css" href="./cheeser_game_style.css" />
</head>

<body>	
<img width = "25" height = "44" id="Rat_img" class="hidden_imgs" src="../games_resources/Cheeser/images/rat.png" />
<img width = "25" height = "44" id="RatDead_img" class="hidden_imgs" src="../games_resources/Cheeser/images/rat_dead.png" />
<img width = "40" height = "35" id="FloorHole_img" class="hidden_imgs" src="../games_resources/Cheeser/images/floor_hole.png" />
<img width = "40" height = "35" id="FloorHoleRepaired_img" class="hidden_imgs" src="../games_resources/Cheeser/images/floor_hole_repaired.png" />
<img width = "20" height = "30" id="Hammer_img" class="hidden_imgs" src="../games_resources/Cheeser/images/hammer.png" />
<img width = "20" height = "15" id="Cheese_img" class="hidden_imgs" src="../games_resources/Cheeser/images/cheese.png" />	
<img width = "15" height = "10" id="Crumbs_img" class="hidden_imgs" src="../games_resources/Cheeser/images/crumbs.png" />	
<div id="BackZone">
</div>
<div id="GameRating">
	<table id="GameRatingTable">
		<tr>
			<td>№</td>
			<td>Аватар</td>
			<td>Имя</td>
			<td><img width="25" height="44" src="../games_resources/Cheeser/images/rat_dead.png" /></td>
			<td>Время</td>
		</tr>
		<tr>
			<td>1</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>2</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>3</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>4</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>5</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>6</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>7</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>8</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>9</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	
		<tr>
			<td>10</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
			<td>............</td>
		</tr>	

	</table>
</div>


<div id="GameMenu">
	<div id="GameHeader">
		<div id="GameHeaderText" class="Text">
		KILL RATS
		</div>
	</div>
	<div id="SurvivalGame">
		<div id="SurvivalGameText" class="Text">
		...Выживание...
		</div>
	</div>
	<div id="LevelGame">
		<div id="LevelGameText" class="Text">
		Уровень: 
		</div>
	</div>
</div>

<div id="GameResult">
	<div id="ResultStatus">
		<div id="ResultStatusText" class="Text">
		</div>
	</div>
	<div id="ResultBlock">
		<div id="RatsKilledResult">
			<div id="RatsKilledResultText" class="Text">
			</div>
		</div>
		<div id="TimeResult">
			<div id="TimeResultText" class="Text">
			</div>
		</div>
	</div>
	<div id="RestartButton">
		<div id="RestartButtonText" class="Text">
		Еще?
		</div>
	</div>
</div>




<div id="RightBigRatImageDiv">
</div>
<div id="LeftBigRatImageDiv">
</div><script>
	var W = 1000; // ширина
	var H = 970; // высота
	
	var GameContainer = null; // контейнер игры
	var MainStage = null; // уровень
	var MainLayer = null; // главный слой
	var Rats = null; // массив мышей
	var FloorHoles = null; // массив дыр
	var Foods = null; // массив для пищи
	var Weapon = null; // это оружие
	var InitDatas = null; // объект, в котором будут храниться данные инициализации

	var gameProcessTimer = null; // для setInterval
	// режим игры!
	// survival, level
	var GAMEMODE = "survival";
	var CurrentSurvivalModeParameters;
	var CurrentLevelModeParameters;
	
	// основной объект статистика!
	var gamestats;
	
	// объект  XMLHttpRequest
	var xmlhttp;	
	// пересылаемые данные
	var SendDatas;
	// данные ВК
	var MyVK;
	

function VK_VARS() {
	this.user_id = "0";
	answr = location.search;
	answr = answr.split("&");
	for (var i = 0; i < answr.length; i++) {
		answr[i] = answr[i].split('=');//Создание двумерного массива
		this[answr[i][0]] = answr[i][1];//Создание объекта, со свойствами двумерного массива.
	}
	if (this.user_id == 0) {
		this.user_id = this.viewer_id;
	}
};

	MyVK = new VK_VARS();


function setRating(json_params_string)
{
//	window.alert(json_params_string);
	console.log(json_params_string);
	var ServerAnswerDatas = JSON.parse(json_params_string);
	if (ServerAnswerDatas.server_answer == "HAVE_RATING")
	{
		ids_str = "";
		for(var i = 0; i < ServerAnswerDatas.result_datas.best_rating.length; i++)
		{
			if (i == (ServerAnswerDatas.result_datas.best_rating.length - 1))
			{
				ids_str += "" + ServerAnswerDatas.result_datas.best_rating[i].vk_id;
			}
			else
			{
				ids_str += "" + ServerAnswerDatas.result_datas.best_rating[i].vk_id + ",";
			}
		}
		VK.api("users.get", {"user_ids": ids_str, "fields": "photo_50"}, function (data) {
			// действия с полученными данными
			GetsObj = document.getElementById("GameRatingTable");
			for (var i = 0; i < data.response.length; i++)
			{
				GetsObj.children[0].children[i+1].children[0].innerHTML = i+1;
				GetsObj.children[0].children[i+1].children[1].innerHTML = "<a href='http://vk.com/id" + data.response[i].id +"'><img src='" + data.response[i].photo_50 + "' /></a>";
				GetsObj.children[0].children[i+1].children[2].innerHTML = data.response[i].first_name;
				GetsObj.children[0].children[i+1].children[3].innerHTML = ServerAnswerDatas.result_datas.best_rating[i].rats_killed_max;
				GetsObj.children[0].children[i+1].children[4].innerHTML = gamestats.getTime(ServerAnswerDatas.result_datas.best_rating[i].time_max);
			}
		});
	} else
	{
		console.log(ServerAnswerDatas.server_answer);
	}
}

function setResults(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	if (ServerAnswerDatas.server_answer == "HAVE_DATA")
	{
		/// здесь могут быть косяки!		
		CurrentLevelModeParameters.CurrentLevelNumber = ServerAnswerDatas.result_datas.user_results.level_max;
		
	} else
	{
		console.log(ServerAnswerDatas.server_answer);
	}
}

function saveResults(json_params_string)
{
	var ServerAnswerDatas = JSON.parse(json_params_string);
	
	if (ServerAnswerDatas.server_answer == "DATA_UPDATED" || 
			ServerAnswerDatas.server_answer == "DATA_SAVED")
	{
		console.log(ServerAnswerDatas.server_answer);
	} else
	{
		console.log(ServerAnswerDatas.server_answer);
	}
}

function getRatingRequest()
{
	SendDatas = {
		Operation: "get_rating_by_num",
		RateNum: 10
	};
	// кодируем показания для передачи
	SendDatas = "Datas=" + JSON.stringify(SendDatas);
	doRequest(function() {
		if (xmlhttp.readyState == 4) {
			 if(xmlhttp.status == 200) {
				setRating(xmlhttp.responseText);
			 }
		}
	},
	SendDatas);
}

function getResultsRequest(json_params)
{
	SendDatas = {
		Operation: "get_result_by_vk_id",
		vk_id: json_params.MyVK.user_id
	};
	// кодируем показания для передачи
	SendDatas = "Datas=" + JSON.stringify(SendDatas);
	
	xmlhttp1 = new XMLHttpRequest();
	xmlhttp1.open('POST', './cheeser_game_funcs.php', false);
	xmlhttp1.onreadystatechange = function () {
		if (xmlhttp1.readyState == 4) {
			 if(xmlhttp1.status == 200) {
				setResults(xmlhttp1.responseText);
			 }
		}

	};
	xmlhttp1.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp1.send(SendDatas);

}

// передавать
// json_params.gamestats
// json_params.CurrentLevelModeParameters
// json_params.MyVK
function saveResultsRequest(json_params)
{
	// снимаем показания!
	SendDatas = {
		Operation: "save_result",
		RatsKilled: json_params.gamestats.RatsKilledCounter,
		Time: json_params.gamestats.Timer,
		vk_id: json_params.MyVK.user_id,
		Level: json_params.CurrentLevelModeParameters.CurrentLevelNumber,
		RateNum: 10
	};
	// кодируем показания для передачи
	SendDatas = "Datas=" + JSON.stringify(SendDatas);
	doRequest(function() {
		if (xmlhttp.readyState == 4) {
			 if(xmlhttp.status == 200) {
				saveResults(xmlhttp.responseText);
			 }
		}
	},
	SendDatas);
}
	
function doRequest (onFunction, SendDatas) 
{
	xmlhttp = new XMLHttpRequest();
	xmlhttp.open('POST', './cheeser_game_funcs.php', true);
	xmlhttp.onreadystatechange = onFunction;
	xmlhttp.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");
	xmlhttp.send(SendDatas);
	
}	


	
	
function SurvivalModeParameters () {
	
	this.TimeForCreateNewFloorHole = 300;
	this.KilledRatsCountForCreateNewFood = 10;
	this.CurrentLevelNumber = null;
	this.StartFloorHolesCount = 2;
	this.StartFoodsCount = 15;
	this.InitDatasFloorHoleHealthStep = 50;
	this.InitDatasFloorHoleHealthStepTime = 30;
	this.InitDatasRatHealthStep = 25;
	this.InitDatasRatHealthStepTime = 30;
	this.InitDatasFoodHealthStep = 30;
	this.InitDatasFoodHealthStepTime = 30;
};

function LevelModeParameters () {
	
	this.TimeForCreateNewFloorHole = null;
	this.KilledRatsCountForCreateNewFood = 10;
	this.CurrentLevelNumber = 1;
	this.StartFloorHoleCount = null;
	this.StartFoodsCount = 2;
	this.InitDatasFloorHoleHealthStep = 50;
	this.InitDatasFloorHoleHealthStepTime = 30;
	this.InitDatasRatHealthStep = 30;
	this.InitDatasRatHealthStepTime = 30;
	this.InitDatasFoodHealthStep = 30;
	this.InitDatasFoodHealthStepTime = 30;

};
LevelModeParameters.prototype.increaseCurrentLevelNumber = function ()
{
	this.CurrentLevelNumber++;
}

CurrentSurvivalModeParameters = new SurvivalModeParameters();
CurrentLevelModeParameters = new LevelModeParameters();
	

////////////////// My GameTimer CLASS////////////////////////////
////////////////////////////////////////////////////////////////
// json_params:
// {
//	StartTime: 0, - 
//	EndTime: 5, - время в секундах
//	FuncContext: this,
//	Parameters: {}
//	CalledFunction : function() {}
// }
function _GameTimer (json_params)
{
	this.Members = {};
	this.Members.CurrentTime = null;
	this.Members.EndTime = null;
	this.Members.CalledFunction = null;
	this.Members.FuncResultAnswer = null;
	this.Members.FuncContext = null;
	this.Members.Parameters = null;
	// значения:
	// free, working
	this.Members.Status = "free";
	if (json_params !== undefined)
	{
		this.set();
	}
}

_GameTimer.prototype.increaseTime = function (Value)
{
	if (this.Members.Status ==  "working")
	{
		if(Value !== undefined)
			this.Members.CurrentTime += Value;
	}
}

_GameTimer.prototype.checkTimer = function (Value)
{
	if (this.Status == "working")
	{
		if(this.CurrentTime >= this.Members.EndTime)
		{
			this.Members.CalledFunction();
		}
		this.Members.CurrentTime = null;
		this.Members.EndTime = null;
		this.Members.Status = "free";		
	}
}

_GameTimer.prototype.set = function (json_params) 
{
	if(this.Status == "free")
	{
		if (json_params !== undefined)
		{
			if (json_params.EndTime !== undefined)
			{
				this.Members.EndTime = json_params.EndTime * 1000 + getTime();
				
			} else
			{
				console.log(this.constructor.name + " have no EndTime parameter");
				return;
			}
			if (json_params.CalledFunction !== undefined)
			{
				this.Members.CalledFunction = json_params.CalledFunction;
			} else
			{
				console.log(this.constructor.name + " have no CalledFunction parameter");
				return;
			}
			if (json_params.StartTime !== undefined)
			{
				this.Members.CurrentTime = json_params.StartTime;
			} else
			{
				console.log(this.constructor.name + " have no StartTime parameter");
				return;
			}			this.Members.Status = "working";			
		}
	}
}
////////////////////// END OF _GameTimer CLASS /////////////////////////////////////
///////////////////////////////////////////////////////////////////////////////////

function _GameStats () { // статистика!
		this.Div = document.createElement("div");
		this.Div.setAttribute("id", "GameStats");
		this.Div.style.position = "fixed";
		this.Div.style.left = "0px";
		this.Div.style.top = "0px";
		this.Div.style.width = W + "px";
		this.Div.style.height = "30px";
		this.Div.style.backgroundColor = "blue";
		this.Div.style.borderBottom = "2px solid red";
		document.body.appendChild(this.Div);
		
		this.RatsKilledDiv = document.createElement("div");		
		this.RatsKilledDiv.setAttribute("class", "StatsDivs");
		this.Div.appendChild(this.RatsKilledDiv);

		this.FoodsDiv = document.createElement("div");		
		this.FoodsDiv.setAttribute("class", "StatsDivs");
		this.Div.appendChild(this.FoodsDiv);

		this.FloorHolesDiv = document.createElement("div");		
		this.FloorHolesDiv.setAttribute("class", "StatsDivs");
		this.Div.appendChild(this.FloorHolesDiv);
		
		this.TimerDiv = document.createElement("div");		
		this.TimerDiv.setAttribute("class", "StatsDivs");
		this.Div.appendChild(this.TimerDiv);		
		
		this.RatsKilledCounter = 0; // счетчик убитых крыс
		// этот параметр должен контролировать создание только 1 сыра!
		this.LastFoodAddRatsKilledCount = 0;
		this.FoodsCounter = 0; // счетчик оставшейся пищи
		this.FloorHolesCounter = 0; // количество дыр!
		
		this.RatsCreatingTimer = 0; // таймер, который засекает время, для создания крысы
		this.Timer = 0; // таймер, засекающий время продолжительности игры.
		this.FPS = 0.1; // FPS, в секундах!

		
		this.updateDivs();
};

_GameStats.prototype.updateDivs = function () 
{
	this.RatsKilledDiv.innerHTML = "Крыс убито: " + this.RatsKilledCounter;
	this.FoodsDiv.innerHTML = "Пищи осталось: " + this.FoodsCounter;
	this.FloorHolesDiv.innerHTML = "Дыр в полу: " + this.FloorHolesCounter;
	this.TimerDiv.innerHTML = "Время: " + this.getTime(this.Timer);
}

_GameStats.prototype.getTime = function (Value)
{		if (Value !== undefined)
		{
			minutes = Math.round(((Value - (Value %60)) / 60));
			seconds = Math.round((Value % 60));
			if(minutes < 10)
				minutes = "0" + minutes;
			if (seconds < 10)
				seconds = "0" + seconds;
			return (minutes + ":" + seconds);
		}
		else
		{
			return ((this.Timer - (this.Timer%60)) / 60 + ":" + (this.Timer % 60));
		}
}


_GameStats.prototype.increaseRatsKilledCounter = function () 
{
	this.RatsKilledCounter++;
	this.updateDivs();
}

_GameStats.prototype.reduceRatsKilledCounter = function () 
{
	this.RatsKilledCounter--;
	this.updateDivs();
}

_GameStats.prototype.increaseFoodsCounter = function () 
{
	this.FoodsCounter++;
	this.updateDivs();
}

_GameStats.prototype.reduceFoodsCounter = function () 
{
	this.FoodsCounter--;
	this.updateDivs();
}

_GameStats.prototype.increaseFloorHolesCounter = function () 
{
	this.FloorHolesCounter++;
	this.updateDivs();
}

_GameStats.prototype.reduceFloorHolesCounter = function () 
{
	this.FloorHolesCounter--;
	this.updateDivs();
}

_GameStats.prototype.increaseTimer = function (Value)
{
	this.Timer += Value;
	++this.RatsCreatingTimer;
}

_GameStats.prototype.clearStats = function ()
{
		this.RatsKilledCounter = 0; // счетчик убитых крыс
		this.FoodsCounter = 0; // счетчик оставшейся пищи
		this.FloorHolesCounter = 0; // количество дыр!
		this.Timer = 0; // таймер, засекающий время продолжительности игры.
		this.LastFoodAddRatsKilledCount = 0;
		this.updateDivs();
}

gamestats = new _GameStats();


////////////////////////////	____CLASSES_______ //////////

////////////////////////////		_Rat class /////////////////
// принимает параметры:
// 
function _Rat (json_params) 
{
		this.Members = {};
		
		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = document.getElementById("Rat_img");
		this.Members.ImgObjs.Attack = document.getElementById("Rat_img");
		this.Members.ImgObjs.Dead = document.getElementById("RatDead_img");
		this.Members.ImgObjs.Damage = document.getElementById("Rat_img");

		
		
		
		this.Members.Layer = null;
		
		this.Members.Image = new Konva.Image();
		this.Members.Health = null; // здоровье, которое будет убывать, когда мы будем их бить.
		this.Members.MaxHealth = null; // содержит max значение здоровья для этой особи.
		this.Members.Speed = null;  // скорость движения изображения
		this.Members.SpeedLimit = null; // лимит, который не может быть превышен
		this.Members.SpeedFactor = null;  // Фактор, на кот умнож текущая скорость
		this.Members.Step = null; // шаг скорости который будет прибавляться к текущей
		this.Members.Color = null; // цвет... не знаю зачем, пока.
		//		this.Members.AttackDistance = null; // дистанция атаки
		this.Members.Damage = null; //
		this.Members.DamageFactor = null; // фактор, на кот умножается 
		this.Members.Target = null;
		// положение X и Y берутся в Image
				
		this.Members.Status = null;
		this.Members.AttackPoint = {};
				/// Проработать установку значений по-умолчанию
		if (json_params !== undefined)
		{
			this.init(json_params);
		}
		
		this.Image().image(this.ImgObjs().Default);
		this.Image().offsetX(this.Image().width() / 2);
		this.Image().offsetY(this.Image().height() /2);
		this.Image().rotation(3/2 * 180);
		this.Layer().add(this.Image());
		this.Image().RatObj = this;
		/// возможно здесь косяк!!!
		this.Image().on('click', function (event) {
			event.target.RatObj.onClick({"Weapon" : Weapon});
		});
		if (json_params.Scale !== undefined)
		{
			this.Image().width(this.Image().width() * json_params.Scale.x);
			this.Image().height(this.Image().height() * json_params.Scale.y);
			this.Image().draw();
		}
		this.Layer().draw();

		// div отображающий значение жизни	
		this.Members.HealthDiv = document.createElement("div");
		this.Members.HealthDiv.setAttribute("class", "RatHealthDiv");
		document.body.appendChild(this.Members.HealthDiv);


		console.log("_Rat: Я родился");
		console.log("_Rat: Offset X: " + this.Image().offsetX() + " Y: " + this.Image().offsetY());



}	
////////////////////////////////////////////////////////////
// get/set members functions!!!!!!!!!!!!!!////////////////

_Rat.prototype.controlHealthDiv = function()
{
//	this.controlHealthDivColor();
	this.setHealthDivPosition();
	this.updateHealthDivInnerHTML();
}

// устанавливает позицию для дива со значением процентов от здоровья
_Rat.prototype.setHealthDivPosition = function()
{
	this.Members.HealthDiv.style.left = this.X() - this.Width() + "px";
	this.Members.HealthDiv.style.top = (this.Y()) + "px";
}

// контролирует изменение цвета для процента здоровья
_Rat.prototype.controlHealthDivColor = function ()
{
	factor = this.Health() / this.MaxHealth();
	if(factor > 0.8)
	{
		this.Members.HealthDiv.style.color = "green";
		return;
	} else if(factor > 0.4)
	{
		this.Members.HealthDiv.style.color = "yellow";
		return;
	} else
	{
		this.Members.HealthDiv.style.color = "red";
		return;
	}
}

_Rat.prototype.updateHealthDivInnerHTML = function()
{
	this.Members.HealthDiv.style.width = (this.Health() / this.MaxHealth() * 50)  + "px";
}

_Rat.prototype.removeHealthDivFromBody = function()
{
	document.body.removeChild(this.Members.HealthDiv);
}

_Rat.prototype.isDead = function ()
{
	if (this.Status() == "Dead")
	{
		return 1;
	} else
	{
		return 0;
	}
}

_Rat.prototype.isGoing = function ()
{
	if (this.Status() == "Going")
	{
		return 1;
	} else
	{
		return 0;
	}
}


_Rat.prototype.Layer = function(Value)
{
	if (Value !== undefined)
	{
		this.Members.Layer = Value;
	} else
	{
		return this.Members.Layer;
	}
}


_Rat.prototype.Speed = function(Speed)
{
	if (Speed !== undefined)
	{
		this.Members.Speed = Speed;
	} else
	{
		return this.Members.Speed;
	}
}

_Rat.prototype.SpeedLimit = function(SpeedLimit)
{
	if (SpeedLimit !== undefined)
	{
		this.Members.SpeedLimit = SpeedLimit;
	} else
	{
		return this.Members.SpeedLimit;
	}
}

_Rat.prototype.SpeedFactor = function(Value)
{
	if (Value !== undefined)
	{
		this.Members.SpeedFactor = Value;
	} else
	{
		return this.Members.SpeedFactor;
	}
}


_Rat.prototype.Step = function(Value)
{
	if (Value !== undefined)
	{
		this.Members.Step = Value;
	} else
	{
		return this.Members.Step;
	}
}

_Rat.prototype.X = function (X)
{
	if (X !== undefined)
	{
		this.Members.Image.x(X);
	} else
	{
		return this.Members.Image.x();
	}
}
_Rat.prototype.Y = function (Y)
{
	if (Y !== undefined)
	{
		this.Members.Image.y(Y);
	} else
	{
		return this.Members.Image.y();
	}
}

_Rat.prototype.Health = function (Health)
{
	if (Health !== undefined)
	{
		this.Members.Health = Health;
		console.log(this.constructor.name + " health: " + this.Members.Health);
	} else
	{
		return this.Members.Health;
	}
}

_Rat.prototype.MaxHealth = function (maxhealth)
{
	if (maxhealth !== undefined)
	{
		this.Members.MaxHealth = maxhealth;
		console.log(this.constructor.name + " health: " + this.Members.MaxHealth);
	} else
	{
		return this.Members.MaxHealth;
	}
}

_Rat.prototype.Height = function (Height)
{
	if (Height !== undefined)
	{
		this.Members.Image.height(Height);
	} else
	{
		return this.Members.Image.height();
	}
}

_Rat.prototype.Width = function (Width)
{
	if (Width !== undefined)
	{
		this.Members.Image.width(Width);
	} else
	{
		return this.Members.Image.width();
	}
}

_Rat.prototype.Damage = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Damage = Value;
	} else
	{
		return this.Members.Damage;
	}
}

_Rat.prototype.DamageFactor = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.DamageFactor = Value;
	} else
	{
		return this.Members.DamageFactor;
	}
}

_Rat.prototype.AttackDistance = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.AttackDistance = Value;
	}else
	{
		return this.Members.AttackDistance;
	}
}


_Rat.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Rat.prototype.Image = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}

_Rat.prototype.Status = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Status = Value;
		console.log(this.constructor.name + " " + this.Members.Status);
	} else
	{
		return this.Members.Status;
	}
}

_Rat.prototype.Target = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Target = Value;
	} else
	{
		return this.Members.Target;
	}
}


////////////////////////////////////////////////////////////
// увеличение скорости

// данная функция просто будет вызываться в главном процессе,
// крыса будет сама вести свое существование!
//json_params должны быть следующие:
// Targets - массив с целями, из которого они будут выбираться!
// 

_Rat.prototype.init = function (json_params)
{

	if (json_params)
	{
		
		if (json_params.ImgObjs)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Image)
		{
			this.Image(json_params.Image); // изобрважение
		}
		if (json_params.Layer)
		{
			this.Layer(json_params.Layer); // слой
		}
		if (json_params.Health)
		{
			this.Health(json_params.Health); // здоровье, которое будет убывать, когда мы будем их бить.
			this.MaxHealth(json_params.Health); // максимальное здоровье устанавливается из того же параметра
		}					
		if (json_params.Speed)
		{
			this.Speed(json_params.Speed);  // скорость движения изображения
		}					
		if (json_params.SpeedLimit)
		{
			this.SpeedLimit(json_params.SpeedLimit); // лимит, который не может быть превышен
		}					
		if (json_params.SpeedFactor)
		{
			this.SpeedFactor(json_params.SpeedFactor);  // Фактор, на кот умнож текущая скорость
		}					
		if (json_params.Step)
		{
			this.Step(json_params.Step); // шаг скорости который будет прибавляться к текущей
		}					
		if (json_params.Color)
		{
			this.Color(json_params.Color); // цвет... не знаю зачем, пока.
		}					
		if (json_params.Width)
		{
			this.Width(json_params.Width); // ширина крысы (Image)
		}					
		if (json_params.Height)
		{
			this.Height(json_params.Height); // размер изображения 
		}					
		if (json_params.X)
		{
			this.X(json_params.X); // положение X копируется в Image
		}					
		if (json_params.Y)
		{
			this.Y(json_params.Y); // положение Y копируется в Image
		}					
		if (json_params.AttackDistance)
		{
			this.AttackDistance(json_params.AttackDistance); // дистанция атаки
		}					
		if (json_params.Damage)
		{
			this.Damage(json_params.Damage); //
		}					
		if (json_params.DamageFactor)
		{
			this.DamageFactor(json_params.DamageFactor); // фактор, на кот умножается 
		}					
		if (json_params.Target)
		{
			this.Target(json_params.Target);
		}					
		if (json_params.Status)
		{		
			this.Status(json_params.Status);
		}					

	}
	
}

// функция, вызываемая в основном цикле!
_Rat.prototype.Life = function (json_params)
{
	// если я мертва - то ничего не делать!
	if (this.isDead())
		return;
	
	// изменяем содержимое div в котором лежит здоровье
	this.controlHealthDiv();
		// выбираем цель из полученного списка!	
	// если у нас нет цели, то выбирваем!	
	if (this.Target() == null)
	{
		if (json_params.Targets !== undefined)
		{
			if (json_params.Targets.length == 0)
			{
				console.log(this.constructor.name + ": have no food....");
				return;
			}
		}
		this.select1of5Target(json_params);
		// поворачиваемся к цели!
		this.turnToTarget({"Target" : this.Target()});
		// идем к цели
		this.comeTo({"Target" : this.Target()});
	} else 
	// если цель есть!
	{
		if(this.Status() == "Live")
		{
		// поворачиваемся к цели!
		this.turnToTarget({"Target" : this.Target()});
		// идем к цели
		this.comeTo({"Target" : this.Target()});
		}
		// если мы доели пищу - обнуляем ее, и ищем новую!		
		if (this.Target().isEaten())
		{
			this.Target(null);
			this.Status("Live");
		} else
		// если мы сейчас идем к цели - ничего не делаем...
		// если мы можем атаковать - атакуем....
		if (this.isCanAttack()){
			this.attackTarget({"Target" : this.Target()});
		}
	}
	
}


/*
// на дистанции атаки!
_Rat.prototype.isAtAttackDistance = function (json_params)
{
	if (this.Target() != null)
	{
		this.calculateAttackDistance();
		if (this.AttackDistance() <= 0)
		{
			return 1; // на дистанции атаки
		} else
		{
			return 0; // не на дистанции атаки!
		}
	}
	else 
	{
			console.log("from _Rat.isAtAttackDistance: Нет цели!");
			return 0;
	}		
}
*/

// передается массив с целями, которые еще присутствуют в игре:
// данные вида {"Targets" : targets}

_Rat.prototype.selectNearestTarget = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.selectNearestTarget: Targets array is empty!!!!");
				return;
			}
			
			this.Members.Target = json_params.Targets[0]; // сначала выбираем 0 элемент
			
			if (json_params.Targets.length == 1)
			{
				return;
			}
			
			for(var i = 1; i < json_params.Targets.length; i++)
			{
				// если X + Y до новой цели меньше, чем до текущей, то меняем текущую цель на новую 
				if(Math.abs(json_params.Targets[i].X() - this.X()) + Math.abs(json_params.Targets[i].Y() - this.Y()) < 
					 Math.abs(this.Members.Target.X() - this.X()) + Math.abs(this.Members.Target.Y() - this.Y()))
					 {
						 this.Members.Target = json_params.Targets[i];
					 }
			}
		}
	}
}

_Rat.prototype.selectRandomTarget = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.selectRandomTarget: Targets array is empty!!!!");
				return;
			}
			
		this.Members.Target = json_params.Targets[Math.round(Math.random() * (json_params.Targets.length - 1))];
		}
	}
}

_Rat.prototype.select1of5Target = function (json_params)
{	
	if (json_params !== undefined) // если есть входные параметры
	{
		if (json_params.Targets !== undefined) //если есть массив с целями
		{
		console.log(this.constructor.name + ": selecting Target from " + json_params.Targets.length);
			if (json_params.Targets.length == 0){ // если в массиве нет
				
				console.log("from _Rat.select1of3Target: Targets array is empty!!!!");
				return;
			}
			if(json_params.Targets.length <= 5)
			{
				this.Members.Target = json_params.Targets[Math.round(Math.random() * (json_params.Targets.length - 1))];
				return;
			} else
			{
				this.timeTargArr = json_params.Targets.slice(0);
				this.selectTimeArr = [];
				this.nearestTargIndex = 0;
				for (var j = 0; j < 5; j++)
				{
					for(var i = 0; i < this.timeTargArr.length; i++)
					{
						// если X + Y до новой цели меньше, чем до текущей, то меняем текущую цель на новую 
						if(Math.abs(this.timeTargArr[i].X() - this.X()) + Math.abs(this.timeTargArr[i].Y() - this.Y()) < 
							 Math.abs(this.timeTargArr[this.nearestTargIndex].X() - this.X()) + Math.abs(this.timeTargArr[this.nearestTargIndex].Y() - this.Y()))
						{
							this.nearestTargIndex = i;
						}	
					}
					this.selectTimeArr.push(this.timeTargArr[this.nearestTargIndex]);
					this.timeTargArr.splice(this.nearestTargIndex, 1);
					this.nearestTargIndex = 0;
				}
				this.Members.Target = this.selectTimeArr[Math.round(Math.random() * (this.selectTimeArr.length - 1))];
				
			}
		}
	}
}


// данная функция рассчитывает и возвращает точку атаки, в которую нужно идти!
// возвращается объект со следующими членами:
// X - положение точки по оси X
// Y - положение точки по оси Y
// duration - время, за которое крысакан должен дойти до точки атаки!
// данные параметры используются в Konva.Image.to()
_Rat.prototype.calculateAttackPoint = function (json_params)
{
	if (json_params.Target !== undefined)
	{
		var toObj = {};
		// вычисление координаты точки X

		if (this.X() < json_params.Target.X())
		{
			toObj.X = Math.round(json_params.Target.X() - json_params.Target.Width() / 2);
		} else
		{
			toObj.X = Math.round(json_params.Target.X() + this.Width() / 2);
		} 
		// вычисление координаты точки Y;
		
		if (this.Y() < json_params.Target.Y())
		{
			toObj.Y = Math.round(json_params.Target.Y() - json_params.Target.Height() / 2 );
		} else
		{
			toObj.Y = Math.round(json_params.Target.Y()  + this.Height() / 2);
		} 

/*
		toObj.X = json_params.Target.X() + this.Image().height() * Math.cos(this.Image().rotation() - 180) + (this.Image().width() / 2) * Math.sin(this.Image().rotation() - 180);
		toObj.Y = json_params.Target.Y() + (this.Image().width()/2)* Math.cos(this.Image().rotation() - 180) + (this.Image().height() / 2) * Math.sin(this.Image().rotation() - 180);
*/		
		timeX = toObj.X - this.X();
		timeY = toObj.Y - this.Y();

		toObj.duration = Math.round(Math.sqrt(timeX * timeX + timeY * timeY) / this.Speed());

		return toObj;
		
	} else
	{
		console.log("from _Rat.calculateAttackPoint: Have no target!");
	}
}

_Rat.prototype.isCanAttack = function ()
{
	if (this.Members.AttackPoint !== undefined && 
			this.Members.AttackPoint !== null && 
			this.Target() !== null && 
			this.Target() !== undefined)
	{
		if (this.X() == this.Members.AttackPoint.X &&
				this.Y() == this.Members.AttackPoint.Y)
		{
			this.Status("CanAttack");
			return 1;
		} else
		{
			return 0;
		}
	} else
	{
		return 0;
	}
	
}

/*
_Rat.prototype.calculateAttackDistance = function (json_params)
{
	if (this.Target() != null)
	{
		if (Math.abs(this.X() - (this.Target().X() + this.Target().Width() / 2)))
		{
		}
		
		
	} else
	{
		console.log("from _Rat.calculateAttackDistance: Нет цели!");
	}
}
*/


_Rat.prototype.increaseSpeed = function (json_params) 
{
	if(json_params !== undefined)
	{
		if(json_params.IncreaseValue !== undefined){
				this.Speed(this.Speed() + json_params.IncreaseValue);	
		}
	}
}

// понижение скорости
_Rat.prototype.reduceSpeed = function (json_params) 
{
	if(json_params !== undefined)
	{
		if(json_params.ReduceValue){
				this.Speed(this.Speed() - json_params.ReduceValue);	
		}
	}
}
_Rat.prototype.stopMoving = function()
{
	if(this.comeTween !== undefined)
	{
		this.comeTween.pause();
	}
	
}
_Rat.prototype.clearListeners = function()
{
	this.Image().off("click");
}

// когда крыса убита
_Rat.prototype.onKill = function (json_params) 
{
	this.Status("Dead");
	this.Image().image(this.ImgObjs().Dead);
	this.Image().draw();
	this.stopMoving();
	this.clearListeners();
	document.body.removeChild(this.Members.HealthDiv);
}
// уменьшение здоровья!
// и проверка, установление смерти!
_Rat.prototype.reduceHealth = function (json_params)
{
	if (json_params !== undefined)
	{
		if(json_params.ReduceValue !== undefined){
				this.Health(this.Health() - json_params.ReduceValue);	
		}
	}
	if (this.Health() <= 0)
	{
		this.onKill();
	}
}
// прибавление здоровья!
_Rat.prototype.increaseHealth = function (json_params)
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}

// когда крысакана атакуют
_Rat.prototype.onAttackMe = function (json_params) 
{
	if (json_params !== undefined)
	{
		if (json_params.Damage)
		{
			this.reduceHealth({ "ReduceValue" : json_params.Damage});
			
		}
	}
}
//атака цели
_Rat.prototype.attackTarget = function (json_params)
{
	if (json_params.Target !== undefined)
	{
		json_params.Target.onAttackMe({"Damage" : this.Damage() * this.DamageFactor()});
	} else
	{
		console.log(this.constructor.name + ".onAttackTarget: Нет цели!");
	}
}

//поворот к цели!
// данная функция вызывается в _Rat.comeTo
_Rat.prototype.turnToTarget = function (json_params) 
{
	this.Image().rotation(90);
	if (json_params.Target !== undefined)
	{
		this.Image().rotate(Math.atan2(json_params.Target.Y() - this.Y(), json_params.Target.X() - this.X()) / Math.PI * 180);
	}
}

/*
_Rat.prototype.startAttackAnim = function (json_params) 
{
	this.Image().image(this.ImgObjs().Attack);
}

_Rat.prototype.stopAttackAnim = function (json_params) 
{
	this.Image().image(this.ImgObjs().Default);	
}

_Rat.prototype.startDeadAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Default));
}

_Rat.prototype.stopDeadAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Dead));	
}
*/
_Rat.prototype.onClick = function (json_params)
{
	if(json_params !== undefined)
	{
		if(json_params.Weapon !== undefined)
		{
			json_params.Weapon.attackTarget({"Target" : this});
			
		}
	}
}

//////////////////////////////////////////////////////
/////////		!DOING FUNCTIONS 	///////////////////////

// функция запускает 

_Rat.prototype.comeTo = function (json_params)
{
		if (json_params)
		{
			if (json_params.Target)
			{
				// здесь параметры движения
				this.Members.AttackPoint = this.calculateAttackPoint(json_params);
				// установка перемещения!
				this.comeTween = new Konva.Tween({
					node: this.Image(),
					x: this.Members.AttackPoint.X,// CheeseImage.x(),
					y: this.Members.AttackPoint.Y,//CheeseImage.y(),
					duration: this.Members.AttackPoint.duration
				});
				// запуск перемещения!
				this.comeTween.play();
				// устанавливаем статус на "Иду"
				this.Status("Going");				
			}
		}
}

/*
_Rat.prototype.comeTo2 = function (json_params)
{
	// заменить на пересечение картинок!
	if (json_params){
		if (json_params.Target) {
			if (this.X() != json_params.Target.X() && this.Y() != json_params.Target.Y())
			{
				if (json_params.Target.X() - this.X() > 0)
				{
					this.X(this.X() - this.Step());
				} else
				{
					this.X(this.X() + this.Step());
				}
				
				if (json_params.Target.Y() - this.Y() > 0)
				{
					this.Y(this.Y() - this.Step() * Math.abs((json_params.Target.Y() - this.Y()) / (json_params.Target.X() - this.X())));
				} else
				{
					this.Y(this.Y() + (this.Step() * Math.abs((json_params.Target.Y() - this.Y()) / (json_params.Target.X() - this.X()))));
				}
			}
		}
	}
}
*/

////////////////////////		_Food 	//////////////////////////////
function _Food (json_params) // это цель, за которой будут охотиться крысы!
{

		this.Members = {};

		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = null;
		this.Members.ImgObjs.Damage = null;
		this.Members.ImgObjs.Eaten = null;
		
		this.Members.Image = new Konva.Image(); // изображение, которое будет перемещаться по экрану. в Konva здесь уже содержатся все необходимые значения 
		this.Members.Layer = null;

		this.Members.Health = null;
		this.Members.X = null;
		this.Members.Y = null;

		this.Members.Status = null;
		
		if (json_params)
		{
				this.init(json_params);
		}
		
		// установка стандартной картинки!
		this.Image().image(this.ImgObjs().Default);
		this.Image().offsetX(this.Image().width() / 2);
		this.Image().offsetY(this.Image().height() /2);

		this.Layer().add(this.Image());
		if (json_params.Scale !== undefined)
		{
			this.Image().width(this.Image().width() * json_params.Scale.x);
			this.Image().height(this.Image().height() * json_params.Scale.y);
			this.Image().draw();
		}

		this.Layer().draw();
		console.log("_Food: Я родился");
}	
// убавление здоровья
// получение ущерба

_Food.prototype.Image = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}


_Food.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Food.prototype.X = function (X)
{
	if (X !== undefined)
	{
		this.Members.Image.x(X);
	} else
	{
		return this.Members.Image.x();
	}
}
_Food.prototype.Y = function (Y)
{
	if (Y !== undefined)
	{
		this.Members.Image.y(Y);
	} else
	{
		return this.Members.Image.y();
	}
}

_Food.prototype.Health = function (Health)
{
	if (Health !== undefined)
	{
		this.Members.Health = Health;
	} else
	{
		return this.Members.Health;
	}
}

_Food.prototype.Layer = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Layer = Value;
	} else
	{
		return this.Members.Layer;
	}
}

_Food.prototype.Height = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Image.height(Value);
	} else
	{
		return this.Members.Image.height();
	}
}

_Food.prototype.Width = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Image.width(Value);
	} else
	{
		return this.Members.Image.width();
	}
}

_Food.prototype.Status = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Status = Value;
		console.log(this.constructor.name + " " + this.Members.Status);
	} else
	{
		return this.Members.Status;
	}
}


_Food.prototype.init = function (json_params)
{
	
	if (json_params.X !== undefined)
	{
		this.X(json_params.X);
	}
	if (json_params.Y !== undefined)
	{
		this.Y(json_params.Y);
	}
	if (json_params.Health !== undefined)
	{
		this.Health(json_params.Health);
	}		
	if (json_params.Layer !== undefined)
	{
		this.Layer(json_params.Layer);
	}	
	if (json_params.ImgObjs !== undefined)
	{
		this.ImgObjs(json_params.ImgObjs);
	}
	if (json_params.Image !== undefined)
	{
		this.Image(json_params.Image);
	}
	if (json_params.Status !== undefined)
	{
		this.Status(json_params.Status);
	}
}

// если пища съедена!
_Food.prototype.isEaten = function ()
{
	if(this.Status() == "Eaten")
	{
		return 1;
	} else
	{
		return 0;
	}
}


// когда пищу съели
_Food.prototype.onEaten = function (json_params) 
{
	this.Image().image(this.ImgObjs().Eaten);
	this.Status("Eaten");
}

// уменьшение жизни
// 
_Food.prototype.reduceHealth = function (json_params)
{
	if (json_params !== undefined)
	{
		if(json_params.Damage !== undefined){
				this.Health(this.Health() - json_params.Damage);	
				console.log(this.constructor.name + ": " + this.Health());
		}
	}
	
	if (this.Health() <= 0)
	{
		this.onEaten();
	}
}
// увеличение здоровья
_Food.prototype.increaseHealth = function (json_params)
{
	if(json_params !== undefined)
	{
		if(json_params.IncreaseValue !== undefined){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}


// параметры:
// Damage -- который будет нанесен еде.
_Food.prototype.onAttackMe = function (json_params)
{
	if (json_params !== undefined)
	{
		if (json_params.Damage !== undefined)
		{
			this.reduceHealth(json_params);
		}
	}	
}


///////////////////////////		_Weapon		/////////////////////////
/// Оружие, которым будем бить крыс!
function _Hammer (json_params)
{
		this.Members = {};

		this.Members.ImgObjs = {};
		this.Members.ImgObjs.Default = null;
		this.Members.ImgObjs.Attack = null;
		
		this.Members.Image = null; // изображение, которое будет перемещаться по экрану. в Konva здесь уже содержатся все необходимые значения 

		this.Members.Health = null; // жизнь, если оружие будет изнашиваться
		this.Members.DamageFactor = null;
		this.Members.Damage = null; // множитель, с которым он оружие бьет по цели
		
		this.Members.Status = null; // множитель, с которым он оружие бьет по цели
		
		if (json_params)
		{
			this.init(json_params);
		}
//	document.body.style.cursor = 'url("../games_resources/Cheeser/images/hammer.png"), pointer';
		console.log("_Hammer: Я родился");
}


_Hammer.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_Hammer.prototype.Image = function (Image) 
{
		if (Image !== undefined)
		{
			this.Members.Image.image(Image);
		}else
		{
			return this.Members.Image;
		}
}

_Hammer.prototype.Damage = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.Damage = Value;
		}else
		{
			return this.Members.Damage;
		}
}

_Hammer.prototype.DamageFactor = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.DamageFactor = Value;
		}else
		{
			return this.Members.DamageFactor;
		}
}

_Hammer.prototype.Status = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.Status = Value;
		}else
		{
			return this.Members.Status;
		}
}

_Hammer.prototype.Health = function (Value) 
{
		if (Value !== undefined)
		{
			this.Members.Health = Value;
		}else
		{
			return this.Members.Health;
		}
}

_Hammer.prototype.init = function (json_params)
{
	if (json_params !== undefined)
	{	
		if (json_params.ImgObjs !== undefined)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Status !== undefined)
		{
			this.Status(json_params.Status); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Image !== undefined)
		{
			this.Image(json_params.Image); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Layer !== undefined)
		{
			this.Layer(json_params.Layer); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Damage !== undefined)
		{
			this.Damage(json_params.Damage); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.DamageFactor !== undefined)
		{
			this.DamageFactor(json_params.DamageFactor); // здоровье, которое будет убывать, когда мы будем их бить.
		}

	}
}

_Hammer.prototype.attackTarget = function (json_params) 
{
	if(json_params !== undefined)
	{
		if(json_params.Target !== undefined)
		{
			json_params.Target.onAttackMe({"Damage": this.Damage() * this.DamageFactor()});
		}
	}
}

_Hammer.prototype.startAttackAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Attack));
}

_Hammer.prototype.stopAttackAnim = function (json_params) 
{
	this.Image().image((this.ImgObjs().Default));	
}

/////////////////////////////////////////////////////////////////////////////////////
///////////////////			_FloorHole CLASS!!!!!!!!!	//////////////////////////////////

function _FloorHole (json_params) 
{
	this.Members = {};
	
	this.Members.ImgObjs = {};
	this.Members.ImgObjs.Default = null;
	this.Members.ImgObjs.Repaired = null;
	
	this.Members.Image = new Konva.Image();
	this.Members.Layer = null;
	this.Members.Status = null; // статус
	
	this.Members.Health = null;
	this.Members.MaxHealth = null; // содержит max значение здоровья для этой особи.

	this.Members.Timers = {};
	this.Members.Timers.ratCreationTime = null;
	
	this.Members.Rats = null;
	
	if (json_params)
	{
		this.init(json_params);
	}
	this.Image().image(this.ImgObjs().Default);
	this.Image().offsetX(this.Image().width() / 2);
	this.Image().offsetY(this.Image().height() /2);

	this.Layer().add(this.Image());
	this.Image().FloorHoleObj = this;
	this.Image().on('click', function (event) {
		event.target.FloorHoleObj.onClick({"Weapon" : Weapon});
	});
		if (json_params.Scale !== undefined)
		{
			this.Image().width(this.Image().width() * json_params.Scale.x);
			this.Image().height(this.Image().height() * json_params.Scale.y);
			this.Image().draw();
		}

		// div отображающий значение жизни	
		this.Members.HealthDiv = document.createElement("div");
		this.Members.HealthDiv.setAttribute("class", "FloorHoleHealthDiv");
		document.body.appendChild(this.Members.HealthDiv);


	this.Layer().draw();
	console.log(this.constructor.name + ": Я родился");
};

_FloorHole.prototype.controlHealthDiv = function()
{
	this.setHealthDivPosition();
	this.updateHealthDiv();
}

// устанавливает позицию для дива со значением процентов от здоровья
_FloorHole.prototype.setHealthDivPosition = function()
{
	this.Members.HealthDiv.style.left = this.X() - this.Width() + "px";
	this.Members.HealthDiv.style.top = this.Y() + "px";
}
_FloorHole.prototype.updateHealthDiv = function()
{
	this.Members.HealthDiv.style.width = (this.Health() / this.MaxHealth() * 70)  + "px";

}
_FloorHole.prototype.appendHealthDivToBody = function()
{
	document.body.appendChild(this.Members.HealthDiv);
}

_FloorHole.prototype.removeHealthDivFromBody = function()
{
	document.body.removeChild(this.Members.HealthDiv);
}



_FloorHole.prototype.ImgObjs = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.ImgObjs = Value;
	}else
	{
		return this.Members.ImgObjs;
	}
}

_FloorHole.prototype.Image = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Image = Value;
	}else
	{
		return this.Members.Image;
	}
}

_FloorHole.prototype.Layer = function (Value) 
{
	if (Value !== undefined)
	{
		this.Members.Layer = Value;
	}else
	{
		return this.Members.Layer;
	}
}

_FloorHole.prototype.X = function (X)
{
	if (X !== undefined)
	{
		this.Members.Image.x(X);
	} else
	{
		return this.Members.Image.x();
	}
}
_FloorHole.prototype.Y = function (Y)
{
	if (Y !== undefined)
	{
		this.Members.Image.y(Y);
	} else
	{
		return this.Members.Image.y();
	}
}

_FloorHole.prototype.Status = function (Value)
{
	if (Value !== undefined)
	{
		this.Members.Status = Value;
		console.log(this.constructor.name + ": " + this.Members.Status);
	} else
	{
		return this.Members.Status;
	}
}

_FloorHole.prototype.Health = function (Health)
{
	if (Health !== undefined)
	{
		this.Members.Health = Health;
		console.log(this.constructor.name + " health: " + this.Members.Health);
	} else
	{
		return this.Members.Health;
	}
}

_FloorHole.prototype.Height = function (Height)
{
	if (Height !== undefined)
	{
		this.Members.Image.height(Height);
	} else
	{
		return this.Members.Image.height();
	}
}

_FloorHole.prototype.Width = function (Width)
{
	if (Width !== undefined)
	{
		this.Members.Image.width(Width);
	} else
	{
		return this.Members.Image.width();
	}
}



_FloorHole.prototype.init = function (json_params)
{
	if (json_params !== undefined)
	{	
		if (json_params.ImgObjs !== undefined)
		{
			this.ImgObjs(json_params.ImgObjs); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.Status !== undefined)
		{
			this.Status(json_params.Status); // здоровье, которое будет убывать, когда мы будем их бить.
		}
		if (json_params.X !== undefined)
		{
			this.X(json_params.X);
		}
		if (json_params.Y !== undefined)
		{
			this.Y(json_params.Y); 
		}
		if (json_params.Health !== undefined)
		{
			this.Health(json_params.Health); 
			this.MaxHealth(json_params.Health);
		}
		if (json_params.Width !== undefined)
		{
			this.Width(json_params.Width); 
		}
		if (json_params.Height !== undefined)
		{
			this.Height(json_params.Height); 
		}
		if (json_params.Image !== undefined)
		{
			this.Image(json_params.Image); 
		}
		if (json_params.Layer !== undefined)
		{
			this.Layer(json_params.Layer); 
		}
		if (json_params.Rats !== undefined)
		{
			this.Rats = json_params.Rats; // массив
		}

	}
}

// для жизни необходим список параметров инициализации!
_FloorHole.prototype.Life = function (json_params)
{
	this.controlHealthDiv();
	
	if (this.Status() == "Open")
	{
			this.createRat(json_params);
	}
}



// функция создания мышей!

_FloorHole.prototype.createRat = function (json_params)
{
	
// могут возникнуть проблемы!	
	var FloorThat = this;
	this.ratCreationTimer = setTimeout(function () 
		{
			InitDatas._Rat.X = FloorThat.X() + (Math.random() * 10 -3);
			InitDatas._Rat.Y = FloorThat.Y() + (Math.random() * 10 -3);
			// добавление крысы 
			FloorThat.Rats.push(new _Rat(InitDatas._Rat));
			// возвращаем статус на открыта!
			FloorThat.Status("Open");
		},
		// здесь параметры
		(Math.random() * (json_params.InitDatas._FloorHole.createRatTimeTo - json_params.InitDatas._FloorHole.createRatTimeFrom) + json_params.InitDatas._FloorHole.createRatTimeFrom) * 1000);
	// устанавливаем статус создание крысы, чтобы нас не удалили из
	// массива!	
	this.Status("RatCreating");
}


// обработка нажатия на картинку дыры

_FloorHole.prototype.onClick = function (json_params)
{
	if(json_params !== undefined)
	{
		if(json_params.Weapon !== undefined)
		{
			json_params.Weapon.attackTarget({"Target" : this});
		}
	}
}


// обработка закалачивания
_FloorHole.prototype.onRepaired = function (json_params)
{
	clearTimeout(this.ratCreationTimer);
	this.Image().image(this.ImgObjs().Repaired);
	this.Status("Repaired");
	this.Image().off("click");
	this.removeHealthDivFromBody();

}
// возвращает, заколочено или нет!
_FloorHole.prototype.isRepaired = function ()
{
		if (this.Status() == "Repaired")
		{
			return 1;
		} else
		{
			return 0;
		}
}

_FloorHole.prototype.MaxHealth = function (maxhealth)
{
	if (maxhealth !== undefined)
	{
		this.Members.MaxHealth = maxhealth;
		console.log(this.constructor.name + " health: " + this.Members.MaxHealth);
	} else
	{
		return this.Members.MaxHealth;
	}
}


// когда крысакана атакуют
_FloorHole.prototype.onAttackMe = function (json_params) 
{
	if (json_params !== undefined)
	{
		if (json_params.Damage)
		{
			this.reduceHealth({ "ReduceValue" : json_params.Damage});
		}
	}
}

// уменьшение здоровья!
// и проверка, установление смерти!
_FloorHole.prototype.reduceHealth = function (json_params)
{
	if (json_params !== undefined)
	{
		if(json_params.ReduceValue !== undefined){
				this.Health(this.Health() - json_params.ReduceValue);	
		}
	}
	if (this.Health() <= 0)
	{
		this.onRepaired();
	}
}
// прибавление здоровья!
_FloorHole.prototype.increaseHealth = function (json_params)
{
	if(json_params)
	{
		if(json_params.IncreaseValue){
				this.Health(this.Health() + json_params.IncreaseValue);	
		}
	}
}


////////////////////////////////////////////////////////////////
////////////////////////////////////////////////////////////////////
/////////////////////		GLOBAL FUNCTIONS AND OBJECTS
///////////////////////////////////////////////////////////////////

////////////////////////////////////////////////////////////////////


var DefaultInitDatas = {
	_Rat : {
		Health: 170,
		ImgObjs: {
			Default: document.getElementById("Rat_img"),
			Dead: document.getElementById("RatDead_img"),
			Damage: document.getElementById("Rat_img"),
			Attack: document.getElementById("Rat_img")
		},
		Speed: 30,
		SpeedLimit: 100,
		SpeedFactor: 1,
		Step: 2,
		Damage: 60,
		DamageFactor: 1,
		Layer: null,
		Status: "Live"
	},
	_Food : {
		Health: 200,
		ImgObjs: {
			Default: document.getElementById("Cheese_img"),
			Eaten: document.getElementById("Crumbs_img"),
		},
		Status: "NotEaten",
		Layer: null
	},
	_Hammer : {
		Damage: 50,
		DamageFactor: 1
	},
	_FloorHole : {
		ImgObjs:{
			Default: document.getElementById("FloorHole_img"),
			Repaired: document.getElementById("FloorHoleRepaired_img")
		},
		Layer: null,
		Status: "Open",
		Rats: null,
		Health: 1550,
		createRatTimeTo: 8,
		createRatTimeFrom: 4
	}
};

function createFloorHole(InitDatas, FloorHoles, W, H)
{
	// рандомно выбирается место создания очередной дыры
	InitDatas._FloorHole.X = Math.random() * (W - 200) + 100;
	InitDatas._FloorHole.Y = Math.random() * (H - 200) + 100;
	// добавляем дыру в массив!!!
	FloorHoles.push(new _FloorHole(InitDatas._FloorHole));
	gamestats.increaseFloorHolesCounter();
}
function createFood(InitDatas, Foods, W, H)
{
	InitDatas._Food.X = Math.random() * (W - 200) + 100;
	InitDatas._Food.Y = Math.random() * (H - 200) + 100;
	
	Foods.push(new _Food(InitDatas._Food));
	gamestats.increaseFoodsCounter();
}
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!
// установка обработчика событий!!! 
// должна быть только один раз!
		$("#SurvivalGame").on("click", function () {
			$("#GameMenu").hide("slow");
			$("#GameRating").hide();
			GAMEMODE = "survival";
			gamestats.clearStats();
			$("#LeftBigRatImageDiv").hide("slow");
			$("#RightBigRatImageDiv").hide("slow");
			Game();
		});
		$("#LevelGame").on("click", function () {
			$("#GameMenu").hide("slow");
			$("#GameRating").hide();
			GAMEMODE = "level";
			gamestats.clearStats();
			$("#LeftBigRatImageDiv").hide("slow");
			$("#RightBigRatImageDiv").hide("slow");
			Game();
		});
// !!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!!

function showGameMenu (json_params)
{
		
		getRatingRequest();
		$("#GameMenu").show("slow");
		$("#GameRating").show("slow");
		$("#LevelGameText").html("Уровень: " +  CurrentLevelModeParameters.CurrentLevelNumber);
		$("#GameResult").hide();
}

function showGameResult (json_params)
{
	if(json_params !== undefined)
	{
		$("#LeftBigRatImageDiv").show("slow");
		$("#RightBigRatImageDiv").show("slow");
		if (json_params.Status == "win")
		{
			$("#ResultStatusText").html("Победа!");
			$("#ResultStatus").css({backgroundColor : "#20e80e"}, 1000);
			// запрос на сохранение данных
			// json_params.gamestats
// json_params.CurrentLevelModeParameters
// json_params.MyVK
		} else
		if (json_params.Status == "loss")
		{
			$("#ResultStatusText").html("Проигрыш");
			$("#ResultStatus").css({backgroundColor : "#cc0000"}, 1000);
		} else
		if (json_params.Status == "stayed")
		{
			$("#ResultStatusText").html("Результаты");
			$("#ResultStatus").css({backgroundColor : "#87ceeb"}, 1000);			
		}
			saveResultsRequest({"gamestats": gamestats, 
													"CurrentLevelModeParameters": CurrentLevelModeParameters, 
													"MyVK": MyVK});
		
		$("#RatsKilledResultText").html("Крыс убито: " + json_params.Stats.RatsKilledCounter);
		$("#TimeResultText").html("Время: " + json_params.Stats.getTime(json_params.Stats.Timer));
		$("#GameResult").show("slow");
		$("#RestartButton").on("click", function () {
			$("#GameResult").hide("slow");
			showGameMenu();
		});

	} else
	{
		console.log("showGameResult function have no parameters!");
	}	
}


// функция обработки игрового процесса!
// будет вызываться постоянно!
// если вся пища съедена - конец игры
function GameProcess (json_params)
{
	gamestats.increaseTimer(gamestats.FPS);
	gamestats.updateDivs();
	
	if (json_params.CurrentGameParams !== "undefined")
	{
		if (gamestats.Timer % json_params.CurrentGameParams.InitDatasRatHealthStepTime  == 0)
		{
			InitDatas._Rat.Health += json_params.CurrentGameParams.InitDatasRatHealthStep;
		}
		if (gamestats.Timer % json_params.CurrentGameParams.InitDatasFloorHoleHealthStepTime  == 0)
		{
			InitDatas._FloorHole.Health += json_params.CurrentGameParams.InitDatasFloorHoleHealthStep;
		}
		if (gamestats.Timer % json_params.CurrentGameParams.InitDatasFoodHealthStepTime  == 0)
		{
			InitDatas._Food.Health += json_params.CurrentGameParams.InitDatasFoodHealthStep;
		}
		if( (json_params.GameMode == "survival") && ((gamestats.RatsCreatingTimer % json_params.CurrentGameParams.TimeForCreateNewFloorHole) == 0))	
		{
			createFloorHole(InitDatas, FloorHoles, W, H);
		}	
		// алгоритм допускает создание множества сыров!!!
		if((gamestats.RatsKilledCounter != 0) && 
			 ((gamestats.RatsKilledCounter % json_params.CurrentGameParams.KilledRatsCountForCreateNewFood) == 0)&&
			  gamestats.LastFoodAddRatsKilledCount != gamestats.RatsKilledCounter)
		{
			createFood(InitDatas, Foods, W, H);
			// не даем создавать доп пищу!
			gamestats.LastFoodAddRatsKilledCount = gamestats.RatsKilledCounter;
		}
	}
		
	for(var i = 0; i < FloorHoles.length; i++)
	{
		// если какая-то из дыр заколочена - удаляем из массива ее!
		if (FloorHoles[i].Status() == "Repaired")
		{
			FloorHoles.splice(i,1);
			gamestats.reduceFloorHolesCounter();
		} else
		// если какая-то из дыр открыта!
		{
			FloorHoles[i].Life({"InitDatas" : InitDatas});
		}
	}
	
	for(var i = 0; i < Rats.length; i++)
	{
		if (Rats[i].Status() == "Dead")
		{
			Rats.splice(i, 1);
			gamestats.increaseRatsKilledCounter();
		} else
		{
			Rats[i].Life({"Targets" : Foods});
		}
	}

	if (Foods.length == 0)
	{
		// останавливаем крыс 
		if(Rats != undefined)
		{
			if(Rats.length != 0)
			{
				for(var i=0; i<Rats.length; i++)
				{
					Rats[i].stopMoving();
					Rats[i].clearListeners();
				}
			}
		}

		clearInterval(gameProcessTimer);
		if (json_params.GameMode == "level")
			showGameResult({"Status" : "loss", "Stats" : gamestats});
		else
			showGameResult({"Status" : "stayed", "Stats" : gamestats});

	} else 
	if (Foods.length != 0 && FloorHoles.length ==	0 && json_params.GameMode == "level")
	{
		// останавливаем крыс 
		if(Rats != undefined)
		{
			if(Rats.length != 0)
			{
				for(var i=0; i<Rats.length; i++)
				{
					Rats[i].stopMoving();
					Rats[i].clearListeners();
				}
			}
		}
		clearInterval(gameProcessTimer);
		// увеличение номера уровня!
		CurrentLevelModeParameters.increaseCurrentLevelNumber();
		showGameResult({"Status" : "win", "Stats" : gamestats});
	}

	for(var i = 0; i < Foods.length; i++)
	{
		if (Foods[i].isEaten())
		{
			Foods.splice(i, 1);
			gamestats.reduceFoodsCounter();
		}
	}
	MainLayer.draw();
	
}	

// инициализация игры
// создание пищи, первой дыры в полу!
function GameInit(json_params)
{
	if (GameContainer != null)
		document.body.removeChild(GameContainer);
	GameContainer = document.createElement("div");
	GameContainer.setAttribute("id", "GameContainer");
	GameContainer.style.position = "absolute";
	GameContainer.style.left = "0px";
	GameContainer.style.top = "30px";
	GameContainer.style.width = W + "px";
	GameContainer.style.height = H + "px";
	document.body.appendChild(GameContainer);
	
	MainStage = new Konva.Stage({
			container: 'GameContainer',
			width: W,
			height: H
	});
	
	MainLayer = new Konva.Layer();
	// массивы объектов!
	
	MainStage.add(MainLayer);
	MainLayer.draw();
	
	// удаляем дивы, которые 
	if(Rats != undefined)
	{
		if(Rats.length != 0)
		{
			for(var i=0; i<Rats.length; i++)
			{
				Rats[i].removeHealthDivFromBody();
			}
		}
	}
	// удаляем дивы, которые 
	if(FloorHoles != undefined)
	{
		if(FloorHoles.length != 0)
		{
			for(var i=0; i<FloorHoles.length; i++)
			{
				FloorHoles[i].removeHealthDivFromBody();
			}
		}
	}
	Rats = [];
	FloorHoles = [];
	Foods = [];

	// нужен только один экземпляр.
	// по умолчанию объект Weapon будет
	// создавать из класса _Hammer
	Weapon = null; // это оружие

	InitDatas = jQuery.extend(true, {}, DefaultInitDatas);
	InitDatas._Rat.Layer = MainLayer;
	InitDatas._FloorHole.Layer = MainLayer;
	InitDatas._Food.Layer = MainLayer;
	InitDatas._FloorHole.Rats = Rats;
	
		if (json_params.FloorHolesCount !== undefined)
		{	
			for (var i = 0; i < json_params.FloorHolesCount; i++)
				createFloorHole(InitDatas, FloorHoles, W, H);
		}
		if (json_params.FoodsCount !== undefined)
		{	
			for (var i = 0; i < json_params.FoodsCount; i++)
			createFood(InitDatas, Foods, W, H);
		}
	
	Weapon = new _Hammer(InitDatas._Hammer);
	MainLayer.draw();	
}

function Game() 
{
	if (GAMEMODE == "survival")
	{
		GameInit({"GameMode" : GAMEMODE, 
							"FloorHolesCount" : CurrentSurvivalModeParameters.StartFloorHolesCount,
							"FoodsCount": CurrentSurvivalModeParameters.StartFoodsCount});
	}else
	if (GAMEMODE == "level")
	{
		GameInit({"GameMode" : GAMEMODE, 
							"FloorHolesCount" : CurrentLevelModeParameters.CurrentLevelNumber,
							"FoodsCount": Math.round(CurrentLevelModeParameters.CurrentLevelNumber * 1.6)}); // количество пищи в зависимости от уровня!
	}
	gameProcessTimer = setInterval(
	function () {
		if (GAMEMODE == "survival")
		{
			GameProcess({"GameMode" : GAMEMODE, "CurrentGameParams" : CurrentSurvivalModeParameters});
		}
		if (GAMEMODE == "level")
		{
			GameProcess({"GameMode" : GAMEMODE, "CurrentGameParams" : CurrentLevelModeParameters});
		}	
	}, gamestats.FPS * 1000);	
};	


//showGameResult({"Status" : "loss", "Stats" : gamestats});

  VK.init(function() { 
     // API initialization succeeded 
     // Your code here 
    VK_initialised = true;    
    var app_id = 5169867;
		
		VK.api("users.get", {"access_token" : MyVK.access_token}, function (data) {
			MyVK.user_id = data.response[0].id;
			getResultsRequest({"MyVK" : MyVK});
			showGameMenu({"Stats" : gamestats});
		});
//    var a=new VKAdman();
//   a.onNoAds(function(){console.log("Adman has no ads")});
//   a.setupPreroll(app_id);
//   admanStat(app_id, MyVK.user_id);    
  }, function() { 
	 // API initialization failed 
	 // Can reload page here 
	 
  }, '5.28'); 



</script>
</body>
</html>

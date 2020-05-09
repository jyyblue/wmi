@extends('dashboard.base')

@section('css')
<!--<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" rel="stylesheet">-->
<style>
	.table th, .table td{
		padding-right:0px;
		padding-left:5px;
	}
	.table th:first-child, .table td:first-child{
		width : 20%;
		text-align : center;
	}
	.card-header *{
		display : inline;
	}
	.arrow, .pg-link{
		border : solid 1px #000;
	}
</style>
@endsection

@section('content')

        <div class="container-fluid">
          <div class="animated fadeIn">
            <div class="row">
              <div class="col-sm-12 col-md-12 col-lg-12 col-xl-12">
                <div class="card">
                    <div class="card-header">
						<span>Date</span>
						<div class="arrow" id="l-arrow">❮</div>
						<div id="links">
							<div class="pg-link" id = "day-before-yesterday">3</div>
							<div class="pg-link" id = "yesterday">4</div>
							<div class="pg-link" id = "today">5</div>
							<div class="pg-link" id = "tomorrow">6</div>
							<div class="pg-link" id = "day-after-tomorrow">7</div>
						</div>
						<div class="arrow" id="r-arrow">❯</div>
					</div>
                    <input type="hidden" id="cur_date" value=/>                      
                    <div class="card-body">
                        <table id="tracklist" class="table table-responsive-sm">
                        <thead>
                          <tr>
                            <td>Hostname</td>
                            <td>Timeline</td>
                            @for($i = 0; $i < 24; $i ++)
								<td><span class="t-vertical">{{$i}}:00</span></td>
							@endfor
                          </tr>
                        </thead>
                        <tbody>
                        </tbody>
                      </table>
                    </div>
                </div>
              </div>
            </div>
          </div>
        </div>

@endsection

@section('javascript')
<!--<script src="https://cdn.datatables.net/1.10.20/js/jquery.dataTables.min.js"></script>-->
<script type="text/javascript">
  $(function () {
    /*var data ={};
    //data['_token'] = '{{ csrf_token() }}';
    //data['user_id'] = $('#user_id').val();
	data['cur_date'] = new Date();
    var table = $('#tracklist').DataTable({
        processing: true,
        serverSide: true,
        responsive: true,
        ajax: 
        {       'url': "{{ route('track.getData') }}",
                'type': 'POST',
                'data': data,
                },
         columns: [
            {data: 'DT_RowIndex', name: 'DT_RowIndex'},
            {data: 'created_at', name: 'created_at'},
            {data: 'app', name: 'app'},
        ]
    });*/
	
	var today = new Date(), cur_date = new Date();
	var day_milliseconds = 86400000;
	var left_icon = '❮', right_icon = '❯';
	var users = {};
	
	changeDate(today);
	
	function changeDate(date){
		if(today - date < day_milliseconds){
			$("#tomorrow").hide();
			$("#day-after-tomorrow").hide();
		} else if(today - date < 2 * day_milliseconds){
			$("#tomorrow").show();
			$("#day-after-tomorrow").hide();
		} else {
			$("#tomorrow").show();
			$("#day-after-tomorrow").show();
		}
		var day_before_yesterday = new Date(date.getTime() - day_milliseconds * 2);
		var yesterday = new Date(date.getTime() - day_milliseconds);
		var tomorrow = new Date(date.getTime() + day_milliseconds);
		var day_after_tomorrow = new Date(date.getTime() + day_milliseconds * 2);
		$("#today").text(getFormattedDate(date));
		$("#day-before-yesterday").text(getFormattedDate(day_before_yesterday));
		$("#yesterday").text(getFormattedDate(yesterday));
		$("#tomorrow").text(getFormattedDate(tomorrow));
		$("#day-after-tomorrow").text(getFormattedDate(day_after_tomorrow));
		var data = {};
		data['cur_date'] = getFormattedDate(cur_date);
		$.ajax({
			'url' : "{{ route('track.getData') }}",
			'type' : 'GET',
			'data' : data,
			'success' : function(res){
				var res = JSON.parse(res);
				users = {};
				for(id in res){
					var data = JSON.parse(res[id].data);
					var hour = parseInt(res[id].hour);
					//var row = {};
					//row[data.username]
					var appType = 0;
					if(data.activeapp == 'chrome.exe' || data.activeapp == 'firefox.exe') appType = 1;
					if(users[data.username] == undefined){
						users[data.username] = {
							'programs' : {},
							'browser' : {}
						};
						if(appType == 1){
							users[data.username].browser[data.activeapp] = [];
						} else {
							users[data.username].programs[data.activeapp] = [];
						}
					}
					if(appType == 1){
						var idx = users[data.username].browser[data.activeapp].indexOf(hour);
						if(idx == -1) users[data.username].browser[data.activeapp].push(hour);
					} else {
						var idx = users[data.username].programs[data.activeapp].indexOf(hour);
						if(idx == -1) users[data.username].programs[data.activeapp].push(hour);
					}
				}
				$('tbody').children().empty();
				insertTable();
			}
		});
	}
	
	function insertTable(){
		for(username in users){
			//$("<tr parent = '" + username + "' type = 'username'><td status = 'right'>" + username + right_icon + "</td>" + "<td></td>".repeat(25) + "</tr>").insertAfter($('tbody'));
			$(getAppendTag(0, username, '')).appendTo($('tbody'));
		}
	}
	
	function getFormattedDate(date){
		var yyyy = date.getFullYear();
		var mm = date.getMonth() + 1;
		if(mm < 10) mm = "0" + mm;
		var dd = date.getDate();
		if(dd < 10) dd = "0" + dd;
		return yyyy + "-" + mm + "-" + dd;
	}
	
	function getAppendTag(type, username, process){
		var ret = "", i;
		if(type == 0){
			ret += "<tr parent = '" + username + "' type = 'username'><td status = 'right'>" + username + right_icon + "</td><td></td>";
			var flag = 0;
			for(i = 0; i < 24; i ++){
				hour = i + 1;
				flag = 0;
				for(id in users[username].browser){
					idx1 = users[username].browser[id].indexOf(hour);
					if(idx1 != -1) flag = 1;
				}
				for(id in users[username].programs){
					idx1 = users[username].programs[id].indexOf(hour);
					if(idx1 != -1) flag = 2;
				}
				if(flag == 0){
					ret += "<td></td>";
				} else if(flag == 1){
					ret += "<td style = 'background-color:red;'></td>";
				} else if(flag == 2){
					ret += "<td style = 'background-color:yellow;'></td>";
				}
			}
			ret += "</tr>";
		} else if(type == 1){
			ret += "<tr><td>Log on time</td><td></td>";
			for(i = 0; i < 24; i ++){
				hour = i + 1;
				var flag = false;
				for(id in users[username].programs){
					idx1 = users[username].programs[id].indexOf(hour);
					if(idx1 != -1) flag = true;
				}
				for(id in users[username].browser){
					idx1 = users[username].browser[id].indexOf(hour);
					if(idx1 != -1) flag = true;
				}
				if(flag == false){
					ret += "<td></td>";
				} else {
					ret += "<td style = 'background-color:green;'></td>";
				}
			}
			ret += "</tr>";
			ret += "<tr type = 'programs'><td status = 'right'>Programs" + right_icon + "</td>" + "<td></td>";
			for(i = 0; i < 24; i ++){
				hour = i + 1;
				var flag = false;
				for(id in users[username].programs){
					idx1 = users[username].programs[id].indexOf(hour);
					if(idx1 != -1) flag = true;
				}
				if(flag == false){
					ret += "<td></td>";
				} else {
					ret += "<td style = 'background-color:yellow'></td>";
				}
			}
			ret += "</tr>";
			ret += "<tr type = 'browser'><td status = 'right'>Browser" + right_icon + "</td>" + "<td></td>";
			for(i = 0; i < 24; i ++){
				hour = i + 1;
				var flag = false;
				for(id in users[username].browser){
					idx1 = users[username].browser[id].indexOf(hour);
					if(idx1 != -1) flag = true;
				}
				if(flag == false){
					ret += "<td></td>";
				} else {
					ret += "<td style = 'background-color:blue'></td>";
				}
			}
			ret += "</tr>";
		} else if(type == 2){
			ret += "<tr><td>" + process + "</td><td></td>";
			for(i = 0; i < 24; i ++){
				hour = i + 1;
				idx1 = users[username].programs[process].indexOf(hour);
				if(idx1 == -1){
					ret += "<td></td>";
				} else {
					ret += "<td style = 'background-color:yellow;'></td>";
				}
			}
			ret += "</tr>";
		} else if(type == 3){
			ret += "<tr><td>" + process + "</td><td></td>";
			for(i = 0; i < 24; i ++){
				hour = i + 1;
				idx1 = users[username].browser[process].indexOf(hour);
				if(idx1 == -1){
					ret += "<td></td>";
				} else {
					ret += "<td style = 'background-color:blue;'></td>";
				}
			}
			ret += "</tr>";
		}
		console.log(type, ret);
		return ret;
	}
	
	$(".arrow, .pg-link").click(function(){
		switch($(this).attr("id")){
			case "l-arrow":
				cur_date = new Date(cur_date.getTime() - day_milliseconds);
				changeDate(cur_date);
			break;
			case "r-arrow":
				cur_date = new Date(cur_date.getTime() + day_milliseconds);
				changeDate(cur_date);
			break;
			case "yesterday":
				cur_date = new Date(cur_date.getTime() - day_milliseconds);
				changeDate(cur_date);
			break;
			case "tomorrow":
				cur_date = new Date(cur_date.getTime() + day_milliseconds);
				changeDate(cur_date);
			break;
			case "day-after-tomorrow":
				cur_date = new Date(cur_date.getTime() + 2 * day_milliseconds);
				changeDate(cur_date);
			break;
			case "day-before-yesterday":
				cur_date = new Date(cur_date.getTime() - 2 * day_milliseconds);
				changeDate(cur_date);
			break;
		}
	})
	
	$('tbody').delegate('tr td:first-child', 'click', function(){
		var type = $(this).parent().attr('type');
		switch(type){
			case 'username':
				var username = $(this).parent().attr('parent');
				var status = $(this).attr('status');
				if(status == 'right'){
					$(this).text(username + left_icon);
					$(this).attr('status', 'left');
					//$("<tr><td>Log on time</td>" + "<td></td>".repeat(25) + "</tr><tr type = 'programs'><td status = 'right'>Programs" + right_icon + "</td>" + "<td></td>".repeat(25) + "</tr><tr type = 'browser'><td status = 'right'>Browser" + right_icon + "</td>" + "<td></td>".repeat(25) + "</tr>").insertAfter($(this).parent());
					$(getAppendTag(1, username, '')).insertAfter($(this).parent());
				} else if(status == 'left'){
					$(this).text(username + right_icon);
					$(this).attr('status', 'right');
					$(this).parent().nextUntil($('[type=username]')).remove();
				}
			break;
			case 'programs':
				var username = $(this).parent().prevUntil($('[type=username]')).prev().attr('parent');
				var status = $(this).attr('status');
				if(status == 'right'){
					$(this).text('Programs' + left_icon);
					$(this).attr('status', 'left');
					for(id in users[username].programs){
						$(getAppendTag(2, username, id)).insertAfter($(this).parent());
					}
				} else if(status == 'left'){
					$(this).text('Programs' + right_icon);
					$(this).attr('status', 'right');
					$(this).parent().nextUntil($('[type=browser]')).remove();
				}
			break;
			case 'browser':
				var username = $(this).parent().prevUntil($('[type=username]')).prev().attr('parent');
				var status = $(this).attr('status');
				if(status == 'right'){
					$(this).text('Browser' + left_icon);
					$(this).attr('status', 'left');
					for(id in users[username].browser){
						$(getAppendTag(3, username, id)).insertAfter($(this).parent());
					}
				} else if(status == 'left'){
					$(this).text('Broser' + right_icon);
					$(this).attr('status', 'right');
					$(this).parent().nextUntil($('[type=username]')).remove();
				}
			break;
		}
	});
  });
</script>
@endsection


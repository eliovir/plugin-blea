<?php

/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$remotes = blea_remote::all();
$id = init('id');
sendVarToJS('plugin', $id);
?>
<div id='div_bleaRemoteAlert' style="display: none;"></div>
<div class="row row-overflow">
	<div class="col-lg-3 col-md-4 col-sm-5 col-xs-5">
		<div class="bs-sidebar">
			<ul id="ul_eqLogic" class="nav nav-list bs-sidenav">
				<a class="btn btn-default bleaRemoteAction" style="width : 100%;margin-top : 5px;" data-action="add"><i class="fa fa-plus-circle"></i> {{Ajouter antenne}}</a>
				<a class="btn btn-warning bleaRemoteAction" style="width : 100%;margin-bottom: 5px;" data-action="refresh"><i class="fas fa-sync"></i> {{Rafraîchir}}</a>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
foreach ($remotes as $remote) {
	$icon = '<i class="fa fa-heartbeat" style="color:green"></i>';
	$last = $remote->getCache('lastupdate','0');
	if ($last == '0' or time() - strtotime($last)>65){
		$icon = '<i class="fa fa-deaf" style="color:#b20000"></i>';
	}
	echo '<li class="cursor li_bleaRemote" data-bleaRemote_id="' . $remote->getId() . '" data-bleaRemote_name="' . $remote->getRemoteName() . '"><a>' . $remote->getRemoteName() . ' '. $icon. ' - v' . $remote->getConfiguration('version','1.0') . ' - ' . $remote->getCache('lastupdate','0') .'</a></li>';
}
?>
			</ul>
		</div>
	</div>
	 <div class="col-lg-19 col-md-8 col-sm-7 col-xs-7 remoteThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
<legend><i class="fa fa-table"></i> {{Mes antennes}}</legend>

<div class="eqLogicThumbnailContainer">
	<div class="cursor bleaRemoteAction logoPrimary" data-action="add" style="width:10px">
      <i class="fa fa-plus-circle"></i>
	  </br>
    <span>{{Ajouter}}</span>
  </div>
  <?php
foreach ($remotes as $remote) {
	echo '<div class="eqLogicDisplayCard cursor col-lg-2" data-remote_id="' . $remote->getId() . '" style="width:10px">';
	echo '<img class="lazy" src="plugins/blea/3rdparty/antenna.png"/>';
	echo '</br>';
	echo '<span class="name">' . $remote->getRemoteName() . '</span>';
	echo '</div>';
}
?>
</div>
</div>

	<div class="col-lg-9 col-md-8 col-sm-7 col-xs-7 bleaRemote" style="border-left: solid 1px #EEE; padding-left: 25px;display:none;">
		<a class="btn btn-success bleaRemoteAction pull-right" data-action="save"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger bleaRemoteAction pull-right" data-action="remove"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>

			<form class="form-horizontal">
					<fieldset>
						<legend><i class="fa fa-arrow-circle-left returnAction cursor"></i> {{Général}}</legend>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Nom}}</label>
							<div class="col-sm-3">
								<input type="text" class="bleaRemoteAttr form-control" data-l1key="id" style="display : none;" />
								<input type="text" class="bleaRemoteAttr form-control" data-l1key="remoteName" placeholder="{{Nom de l'antenne}}"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{IP}}</label>
							<div class="col-sm-3">
								<input type="text" class="bleaRemoteAttr form-control" data-l1key="configuration" data-l2key="remoteIp"/>
							</div>
							<label class="col-sm-1 control-label">{{Port}}</label>
							<div class="col-sm-3">
								<input type="text" class="bleaRemoteAttr form-control" data-l1key="configuration" data-l2key="remotePort"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Utilisateur}}</label>
							<div class="col-sm-3">
								<input type="text" class="bleaRemoteAttr form-control" data-l1key="configuration" data-l2key="remoteUser"/>
							</div>
							<label class="col-sm-1 control-label">{{Mot de passe}}</label>
							<div class="col-sm-3">
								<input type="password" class="bleaRemoteAttr form-control" data-l1key="configuration" data-l2key="remotePassword"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 control-label">{{Équipement}}</label>
							<div class="col-sm-3">
								<input type="text" class="bleaRemoteAttr form-control" data-l1key="configuration" data-l2key="remoteDevice" placeholder="{{ex : hci0}}"/>
							</div>
						</div>
						<?php
						if (method_exists( $id ,'sendRemoteFiles')){
							echo '<div class="form-group">
						<label class="col-sm-2 control-label">{{Envoi des fichiers nécessaires}}</label>
						<div class="col-sm-3">
							<a class="btn btn-warning bleaRemoteAction" data-action="sendFiles"><i class="fa fa-upload"></i> {{Envoyer les fichiers}}</a>
						</div>';
						if (method_exists( $id ,'dependancyRemote')){
							echo '<label class="col-sm-2 control-label">{{Installation des dépendances}}</label>
						<div class="col-sm-3">
							<a class="btn btn-warning bleaRemoteAction" data-action="dependancyRemote"><i class="fa fa-spinner"></i> {{Lancer les dépendances}}</a>
						</div>
						<div class="col-sm-2">
							<a class="btn btn-success bleaRemoteAction" data-action="getRemoteLogDependancy"><i class="far fa-file-alt"></i> {{Log dépendances}}</a>
						</div>';
						}
						echo'</div>';
						}
						if (method_exists( $id ,'launchremote')){
							echo '<div class="form-group">
						<label class="col-sm-2 control-label">{{Gestion du démon}}</label>
						<div class="col-sm-2">
							<a class="btn btn-success bleaRemoteAction" data-action="launchremote"><i class="fa fa-play"></i> {{Lancer}}</a>
						</div>
						<div class="col-sm-2">
							<a class="btn btn-danger bleaRemoteAction" data-action="stopremote"><i class="fa fa-stop"></i> {{Arrêt}}</a>
						</div>
						<div class="col-sm-2">
							<a class="btn btn-success bleaRemoteAction" data-action="getRemoteLog"><i class="far fa-file-alt"></i> {{Log}}</a>
						</div>
						</div>
						<div class="form-group">
						<label class="col-sm-2 control-label">{{Gestion du démon automatique}}</label>
						<div class="col-sm-2">
							<a class="btn btn-danger bleaRemoteAction" data-action="changeAutoModeRemote"></a>
							<input type="hidden" class="bleaRemoteAttr form-control" data-l1key="configuration" data-l2key="remoteDaemonAuto"/>
						</div>
						</div>';
						}
						?>
						<div class="form-group">
						<label class="col-sm-2 control-label">{{Mettre tous les équipements sur cette antenne}}</label>
						<div class="col-sm-2">
							<a class="btn btn-success bleaRemoteAction" data-action="all" data-type="reception"><i class="fas fa-sign-in-alt fa-rotate-90"></i> {{Réception}}</a>
						</div>
						<div class="col-sm-2">
							<a class="btn btn-danger bleaRemoteAction" data-action="all" data-type="emission"><i class="fas fa-sign-in-alt fa-rotate-270"></i> {{Émission}}</a>
						</div>
						</div>
						<div class="alert alert-info">{{La durée d'installation des dépendances sur une antenne peut prendre jusqu'à presque 30 minutes selon les antennes}}</div>
				</div>
						</fieldset>
				</form>
	</div>
</div>

<script>
	function refreshDaemonMode() {
		var auto = $('.bleaRemoteAttr[data-l2key="remoteDaemonAuto"]').value();
		if(auto == 1){
			$('.bleaRemoteAction[data-action=stopremote]').hide();
			$('.bleaRemoteAction[data-action=changeAutoModeRemote]').removeClass('btn-success').addClass('btn-danger');
			$('.bleaRemoteAction[data-action=changeAutoModeRemote]').html('<i class="fa fa-times"></i> {{Désactiver}}');
		}else{
			$('.bleaRemoteAction[data-action=stopremote]').show();
			$('.bleaRemoteAction[data-action=changeAutoModeRemote]').removeClass('btn-danger').addClass('btn-success');
			$('.bleaRemoteAction[data-action=changeAutoModeRemote]').html('<i class="fa fa-magic"></i> {{Activer}}');
		}
	}
	$('.bleaRemoteAction[data-action=refresh]').on('click',function(){
		$('#md_modal').dialog('close');
		$('#md_modal').dialog({title: "{{Gestion des antennes Bluetooth}}"});
		$('#md_modal').load('index.php?v=d&plugin=blea&modal=blea.remote&id=blea').dialog('open');
	});

	$('.bleaRemoteAction[data-action=add]').on('click',function(){
		$('.bleaRemote').show();
		$('.remoteThumbnailDisplay').hide();
		$('.bleaRemoteAttr').value('');
	});

	$('.eqLogicDisplayCard').on('click',function(){
		displaybleaRemote($(this).attr('data-remote_id'));
	});

	function displaybleaRemote(_id){
		$('.li_bleaRemote').removeClass('active');
		$('.li_bleaRemote[data-bleaRemote_id='+_id+']').addClass('active');
		$.ajax({
			type: "POST",
			url: "plugins/blea/core/ajax/blea.ajax.php",
			data: {
				action: "get_bleaRemote",
				id: _id,
			},
			dataType: 'json',
			async: true,
			global: false,
			error: function (request, status, error) {
			},
			success: function (data) {
				if (data.state != 'ok') {
					return;
				}
				$('.bleaRemote').show();
				$('.remoteThumbnailDisplay').hide();
				$('.bleaRemoteAttr').value('');
				$('.bleaRemote').setValues(data.result,'.bleaRemoteAttr');
				setTimeout(function() { refreshDaemonMode(); }, 200);
			}
		});
	}

	function displaybleaRemoteComm(_id){
		$('.li_bleaRemote').removeClass('active');
		$('.li_bleaRemote[data-bleaRemote_id='+_id+']').addClass('active');
		$.ajax({
			type: "POST",
			url: "plugins/blea/core/ajax/blea.ajax.php",
			data: {
				action: "get_bleaRemote",
				id: _id,
			},
			dataType: 'json',
			async: true,
			global: false,
			error: function (request, status, error) {
			},
			success: function (data) {
				if (data.state != 'ok') {
					return;
				}
				$('.bleaRemote').show();
				$('.bleaRemoteAttrcomm').value('');
				$('.bleaRemote').setValues(data.result,'.bleaRemoteAttrcomm');
			}
		});
	}

	$('.li_bleaRemote').on('click',function(){
		displaybleaRemote($(this).attr('data-bleaRemote_id'));
		$('.remoteThumbnailDisplay').hide();
	});

	$('.returnAction').on('click',function(){
		$('.bleaRemote').hide();
		$('.li_bleaRemote').removeClass('active');
		setTimeout(function() { $('.remoteThumbnailDisplay').show() }, 100);
		;
	});

	$('.bleaRemoteAction[data-action=changeAutoModeRemote]').on('click',function(){
		var auto = 1 - $('.bleaRemoteAttr[data-l2key="remoteDaemonAuto"]').value();
		$('.bleaRemoteAttr[data-l2key="remoteDaemonAuto"]').val(auto);
		$('.bleaRemoteAction[data-action=save]').click();
	});

	$('.bleaRemoteAction[data-action=save]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/blea/core/ajax/blea.ajax.php",
			data: {
				action: "save_bleaRemote",
				blea_remote: json_encode(blea_remote),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state !== 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Sauvegarde réussie}}', level: 'success'});
				$('#md_modal').dialog('close');
				$('#md_modal').dialog({title: "{{Gestion des antennes Bluetooth}}"});
				$('#md_modal').load('index.php?v=d&plugin=blea&modal=blea.remote&id=blea').dialog('open');
				setTimeout(function() { displaybleaRemote(data.result.id) }, 200);

			}
		});
	});

	$('.bleaRemoteAction[data-action=sendFiles]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "sendRemoteFiles",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Envoi réussi}}', level: 'success'});
			}
		});
	});

	$('.bleaRemoteAction[data-action=getRemoteLog]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "getRemoteLog",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Log récupéré}}', level: 'success'});
			}
		});
	});

	$('.bleaRemoteAction[data-action=getRemoteLogDependancy]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "getRemoteLogDependancy",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Log récupéré}}', level: 'success'});
			}
		});
	});

	$('.bleaRemoteAction[data-action=launchremote]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "launchremote",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Lancement réussi}}', level: 'success'});
			}
		});
	});
	
	$('.bleaRemoteAction[data-action=all]').on('click',function(){
	var type = $(this).attr('data-type');
	var remoteId=$('.li_bleaRemote.active').attr('data-bleaRemote_id');
	bootbox.confirm('{{Voulez-vous vraiment mettre tous les équipements sur cette antenne en : }}' +$(this).attr('data-type'), function (result) {
		if (result) {
			$.ajax({
				type: "POST",
				url: "plugins/blea/core/ajax/blea.ajax.php",
				data: {
					action: "allantennas",
					remote: "remote",
					remoteId: remoteId,
					type: type,
				},
				dataType: 'json',
				error: function (request, status, error) {
					handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
				},
				success: function (data) {
					if (data.state !== 'ok') {
						$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
						return;
					}
					$('#div_bleaRemoteAlert').showAlert({message: '{{Réussi}}', level: 'success'});
				}
			});
		}
	});
});

	$('.bleaRemoteAction[data-action=dependancyRemote]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "dependancyRemote",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state !== 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Envoi réussi}}', level: 'success'});
			}
		});
	});

	$('.bleaRemoteAction[data-action=stopremote]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "stopremote",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Arrêt réussi}}', level: 'success'});
			}
		});
	});

	$('.bleaRemoteAction[data-action=remotelearn]').on('click',function(){
		var blea_remote = $('.bleaRemote').getValues('.bleaRemoteAttr')[0];
		$.ajax({
			type: "POST",
			url: "plugins/"+plugin+"/core/ajax/"+plugin+".ajax.php",
			data: {
				action: "remotelearn",
				remoteId: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
				state: $(this).attr('data-type'),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#div_bleaRemoteAlert').showAlert({message: '{{Envoi réussi}}', level: 'success'});
			}
		});
	});

	$('.bleaRemoteAction[data-action=remove]').on('click',function(){
		bootbox.confirm('{{Voulez-vous vraiment supprimer cette antenne ?}}', function (result) {
			if (result) {
				$.ajax({
					type: "POST",
					url: "plugins/blea/core/ajax/blea.ajax.php",
					data: {
						action: "remove_bleaRemote",
						id: $('.li_bleaRemote.active').attr('data-bleaRemote_id'),
					},
					dataType: 'json',
					error: function (request, status, error) {
						handleAjaxError(request, status, error,$('#div_bleaRemoteAlert'));
					},
					success: function (data) {
						if (data.state != 'ok') {
							$('#div_bleaRemoteAlert').showAlert({message: data.result, level: 'danger'});
							return;
						}
						$('.li_bleaRemote.active').remove();
						$('.bleaRemote').hide();
						$('.remoteThumbnailDisplay').show();
						$('#md_modal').dialog('close');
						$('#md_modal').dialog({title: "{{Gestion des antennes Bluetooth}}"});
						$('#md_modal').load('index.php?v=d&plugin=blea&modal=blea.remote&id=blea').dialog('open');
					}
				});
			}
		});
	});
window.setInterval(function () {
    displaybleaRemoteComm($('.li_bleaRemote.active').attr('data-bleaRemote_id'));
}, 5000);
</script>

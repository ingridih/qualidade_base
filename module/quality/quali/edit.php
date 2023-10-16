<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";

if($_SESSION['USUTIPO'] != 'QUALIDADE'){ 
	header('Location: /login'); 
}

require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();

$idct = base64_decode($_GET['id']);

$array_lab = array();
$query = $Conexao->query("SELECT E_ID, E_NOME FROM QUALIDADE_LABORATORIO WHERE E_ATIVO = 'A'");
while ($row = $query->fetch()) {
	$array_lab[] = $row;
}

$array_elementos = array();
$query2 = $Conexao->query("SELECT EQ_ID, EQ_NOME, EQ_SIGLA FROM QUALIDADE_ELEMENTOS_QUIMICOS");
while ($row2 = $query2->fetch()) {
	$array_elementos[] = $row2;
}

$identifica = null;
$lab = null;
$tipo = null;
$urgente = null;
$observacao = null;
$contra = null;
$query0 = $Conexao->query("SELECT CA_STATUS CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_LABORATORIO, CA_URGENCIA, CA_OBS_QUALI, CA_OBS_LAB, CA_DATA, CA_CONTRA 
	FROM QUALIDADE_CARTA WHERE CA_ID = ".$idct);
$row0 = $query0->fetch();
if(!empty($row0)){ 
	$identifica = $row0['CA_IDENTIFICACAO_QUALI'];
	$lab = $row0['CA_LABORATORIO'];
	$tipo = $row0['CA_TIPO'];
	$urgente = $row0['CA_URGENCIA'];
	$observacao = $row0['CA_OBS_QUALI'];
	$contra = $row0['CA_CONTRA'];
}



?>
<!DOCTYPE html>
<!--
oooooooo-----ooo---ooo---ooo
ooo---ooo----ooo---ooo---ooo
ooo---oooo---ooo---ooooooooo
ooo---ooo----ooo---ooo---ooo
ooooooo------ooo---ooo---ooo
-->
<html lang="pt-br">
	<!--begin::Head-->
	<head>
        <?php require $_SERVER['DOCUMENT_ROOT']."/menu/meta.php"; ?>
		<?php require $_SERVER['DOCUMENT_ROOT']."/menu/header.php"; ?>
	</head>
	<!--end::Head-->
	<!--begin::Body-->
	<?php
		// Verifica se o cookie "sidebar_minimize_state" está definido
		if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
		// Se o cookie estiver definido como "on", define o atributo como "on"
		$sidebarMinimizeState = "on";
		} else {
		// Caso contrário, define o atributo como "off" ou qualquer outro valor padrão desejado
		$sidebarMinimizeState = "off";
	}
	?>

	<body id="kt_app_body" data-kt-app-sidebar-minimize="<?php echo $sidebarMinimizeState; ?>" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: dark)").matches ? "dark" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<!--=------------------------------------------------- START TOPBAR------------------------------------------------------------------------->
					<?php require $_SERVER['DOCUMENT_ROOT']."/menu/topbar.php"; ?>
				<!--=------------------------------------------------- END TOPBAR------------------------------------------------------------------------->
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--=------------------------------------------------- START MENU LATERAL------------------------------------------------------------------------->
					<?php require $_SERVER['DOCUMENT_ROOT']."/menu/menu.php"; ?>
					<!--begin::Main-->
					<!--=------------------------------------------------- END MENU LATERAL------------------------------------------------------------------------->
					<div class="app-main flex-column flex-row-fluid" id="kt_app_main">
						<!--begin::Content wrapper-->
						<div class="d-flex flex-column flex-column-fluid">
							<!--begin::Content-->
							<div id="kt_app_content" class="app-content flex-column-fluid">
								<!--begin::Content container-->
								<div id="kt_app_content_container" class="app-container container-fluid">
									<!--begin::Row-->
									<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
										<!--begin::Col-->
										<div class="col-xxl-12">
											<!--begin::Engage widget 10-->
											<div class="card card-flush h-md-100">
												<!--begin::Body-->
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0" >
													<!--begin::Wrapper-->
													<div class="mb-10">
														<!--begin::Title-->
														<h3 class="anchor fw-bold mb-8">
                                                            Nova carta para análise
                                                        </h3>
														
                                                        <form class="row g-3" id="form">
															<!--  informações da CT -->
															<input  type="hidden" id="idcarta" value="<?php echo base64_decode($_GET['id']);?> "/>
															<div class="form-group row mb-5 ">
																<div class="col-md-3 fv-row">
																	<label for="ident_quali" class="form-label required ">Indentificação</label>
																	<input type="text" class="form-control" id="ident_quali" name="ident_quali" placeholder="Insira a identificação da CT" value="<?php echo $identifica ?>">
																</div>
																<div class="col-md-3 fv-row">
																	<label for="selectlab" class="form-label required ">Laboratório</label>
																	<select class="form-control" id="selectlab" name="selectlab">
																		<option value="">Selecione um laboratório.</option>
																		<?php 
																			foreach($array_lab as $ar){
																				($lab == $ar['E_ID']) ? $sel = 'selected' : $sel = '';
																				echo '<option value="'.$ar['E_ID'].'" '.$sel.'>'.$ar['E_NOME'].'</option>';
																			}
																		?>
																	</select>
																</div>
																
																<div class="col-md-2">
																	<div class="form-check form-check-solid" style="margin-top: 30px">
																		<input class="form-check-input" type="checkbox" value="checkurgente" id="checkurgente" <?php echo (($urgente == 1) ? 'checked' : '') ?>/>
																		<label class="form-check-label" for="checkurgente">
																			URGENTE
																		</label>
																	</div>
																</div>
																<div class="col-md-2">
																	<div class="form-check form-check-custom form-check-warning form-check-solid" style="margin-top: 30px">
																		<input class="form-check-input" type="checkbox" value="checkcontra" id="checkcontra" <?php echo (($contra != null) ? 'checked' : '') ?> />
																		<label class="form-check-label" for="checkcontra">
																			Contra Prova
																		</label>
																	</div>
																</div>
															</div>
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<label for="obs_quali" class="form-label">Observação Geral</label>
																	<textarea class="form-control" id="obs_quali" name="obs_quali" placeholder="Uma observação para o Laboratório" value=""><?php echo $observacao ?></textarea>
																</div>
														    </div>

															<!-- -- FIM ---  informações da CT -->
															<div class="separator separator-dashed border-primary my-10"></div>
															<!--begin::Repeater-->
															<div id="kt_docs_repeater_nested">
																<!--begin::Form group-->
																<div class="form-group ">
																	<div data-repeater-list="kt_docs_repeater_op">
																		<?php 
																			$contador = 0;
																			$query2 = $Conexao->query("SELECT PO_ID, PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE, PO_QTD, 
																			DATE_FORMAT(PO_DATA_FAB,'%d/%m/%Y') PO_DATA_FAB, PO_REG_MAPA, 
																			DATE_FORMAT(PO_DATA_VALIDADE,'%d/%m/%Y') PO_DATA_VALIDADE, PO_TIPO, PO_NOTA, PO_OBS_LAB, PO_OBS_QUALI, 
																			PO_ID_LAB, PO_ID_SOLICITANTE, PO_DATA_CRIADO, PO_OBS_LAUDO, PO_FORNECEDOR,
																			PO_CLIENTE, PO_METAIS_PESADOS, PO_DATA, PO_APROVADO, PO_CONTRA 
																			FROM QUALIDADE_PRODUTO WHERE PO_ID_CARTA = ".$idct);
																			while ($row2 = $query2->fetch()) {
																				echo '<div data-repeater-item="">
																					<div class="form-group row mb-5 fv-row fv-plugins-icon-container">
																						<input type="hidden" class="idop" name="kt_docs_repeater_op['.$contador.'][idop]" value="'.$row2['PO_ID'].'"/>
																						<div class="col-md-2">
																							<label class="form-label required ">Número da OP</label>
																							<input type="text" class="form-control mb-2 mb-md-0 numero_op" name="kt_docs_repeater_op['.$contador.'][op]" placeholder="Identificação da Ordem de Produção." maxlength="150" value="'.$row2['PO_NUMERO'].'">
																						</div>
																						<div class="col-md-2">
																							<label class="form-label required ">Produto</label>
																							<input type="text" class="form-control mb-2 mb-md-0 produto_op" name="kt_docs_repeater_op['.$contador.'][produto]" placeholder="Produto" maxlength="200" value="'.$row2['PO_PRODUTO'].'">
																						</div>
																						<div class="col-md-3">
																							<label class="form-label required ">Descrição</label>
																							<input type="text" class="form-control mb-2 mb-md-0 desc_op" name="kt_docs_repeater_op['.$contador.'][desc]" placeholder="Descrição do produto" maxlength="150" value="'.$row2['PO_PROD_DESC'].'">
																						</div>
																						<div class="col-md-3">
																							<label class="form-label ">Cliente</label>
																							<input type="text" class="form-control mb-2 mb-md-0 cliente_op" name="kt_docs_repeater_op['.$contador.'][cliente]" placeholder="Insira o Cliente" maxlength="450" value="'.$row2['PO_CLIENTE'].'">
																						</div>
																						<div class="col-md-2">
																							<label class="form-label">Fornecedor</label>
																							<input type="text" class="form-control mb-2 mb-md-0 fornecedor_op" name="kt_docs_repeater_op['.$contador.'][fornecedor_op]" placeholder="Insira o Fornecedor" maxlength="450" autocomplete="off" value="'.$row2['PO_FORNECEDOR'].'"/>
																						</div>
																					</div>
																					<div class="form-group row mb-5 fv-row fv-plugins-icon-container">
																						<div class="col-md-2"> 
																							<label class="form-label required ">Lote</label>
																							<input type="text" class="form-control mb-2 mb-md-0 lote_op" name="kt_docs_repeater_op['.$contador.'][lote]" placeholder="Lote" maxlength="150" value="'.$row2['PO_LOTE'].'">
																						<div class="fv-plugins-message-container invalid-feedback"></div></div>
																						<div class="col-md-2">
																							<label class="form-label">Nota/Série</label>
																							<input type="text" class="form-control mb-2 mb-md-0 nota_op" name="kt_docs_repeater_op['.$contador.'][nota]" placeholder="Insira a nota e série" maxlength="50" value="'.$row2['PO_NOTA'].'">
																						<div class="fv-plugins-message-container invalid-feedback"></div></div>
																						<div class="col-md-2">
																							<label class="form-label required ">Quantidade</label>
																							<input type="text" class="form-control mb-2 mb-md-0 qtd_op" name="kt_docs_repeater_op['.$contador.'][qtd]" placeholder="Insira a quantidade" maxlength="150" value="'.$row2['PO_QTD'].'">
																						<div class="fv-plugins-message-container invalid-feedback"></div></div>
																						<div class="col-md-2">
																							<label class="form-label required ">Data de Fábricação</label>
																							<input type="text" class="form-control mb-2 mb-md-0 maskdata fab_op flatpickr-input" name="kt_docs_repeater_op['.$contador.'][datafab]" placeholder="Insira a data" inputmode="text" value="'.$row2['PO_DATA_FAB'].'">
																						<div class="fv-plugins-message-container invalid-feedback"></div></div>
																						<div class="col-md-2">
																							<label class="form-label required ">Validade</label>
																							<input type="text" class="form-control mb-2 mb-md-0 maskdata validade_op flatpickr-input" name="kt_docs_repeater_op['.$contador.'][validade]" placeholder="Insira a validade" value="'.$row2['PO_DATA_VALIDADE'].'">
																						</div>
																						<div class="col-md-2">
																							<label class="form-label required ">MAPA</label>
																							<input type="text" class="form-control mb-2 mb-md-0 mapa_op" name="kt_docs_repeater_op['.$contador.'][mapa]" placeholder="Reg. Mapa" maxlength="250" value="'.$row2['PO_REG_MAPA'].'">
																						<div class="fv-plugins-message-container invalid-feedback"></div></div>
																					</div>
																					<div class="form-group row mb-5 fv-row fv-plugins-icon-container">
																						<div class="col-md-2">
																							<label class="form-label required">Tipo</label>
																							<select class="form-control tipo_op" id="tipo_op" name="tipo_op">
																								<option value="" selected>Selecione o tipo de amostra.</option>
																								<option value="PA" '.($row2['PO_TIPO'] == 'PA' ? 'selected' : '').'>Produto Acabado</option>
																								<option value="MP" '.($row2['PO_TIPO'] == 'MP' ? 'selected' : '').'>Matéria Prima</option>
																								<option value="MICRO" '.($row2['PO_TIPO'] == 'MICRO' ? 'selected' : '').'>Micronutriente</option>
																							</select>
																						</div>
																						<div class="col-md-2">
																							<div class="form-check" style="margin-top: 30px">
																								<input class="form-check-input metais" type="checkbox" value="metais" id="metais" '.(($row2['PO_METAIS_PESADOS'] == 1) ? 'checked' : '').'>
																								<label class="form-check-label" for="metais">
																									Analisar Metais Pesados
																								</label>
																							</div>
																						</div>
																					</div>
																					<div class="form-group row mb-5">
																						<div class="col-md-10">
																							<label class="form-label">Observação</label>
																							<input type="text" class="form-control mb-2 mb-md-0 obs_op" name="kt_docs_repeater_op['.$contador.'][obsop]" placeholder="Insira a observação se houver." value="'.$row2['PO_OBS_QUALI'].'">
																						</div>
																						<div class="col-md-2">
																							<a href="javascript:;" data-repeater-delete="" class="btn btn-sm btn-flex btn-light-danger mt-3 mt-md-9">
																								<i class="fa-solid fa-trash-can"></i>
																								Apagar OP
																							</a>
																						</div>
																					</div>
																					<div class="alert alert-dismissible bg-primary d-flex flex-column flex-sm-row w-100 p-2 mb-5">
																						<div class="d-flex flex-column text-light pe-0 pe-sm-10">
																							<h4 class="mb-2 text-light">Elementos</h4>
																						</div>
																					</div>
																						<div class="inner-repeater">
																							<div data-repeater-list="kt_docs_repeater_nested_inner" class="mb-5">';
																								$i = 0;
																								$query4 = $Conexao->query("SELECT AE_ID, AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_GARANTIA, AE_VMA, AE_RESULTADO, AE_METODO, AE_QUALI
																								FROM QUALIDADE_ANALISE WHERE AE_PRODUTO_ID = ".$row2['PO_ID']);
																								while ($row4 = $query4->fetch()) {
																									echo '<div data-repeater-item="">
																										<div class="form-group row mb-5">
																											<div class="col-md-2">
																												<select type="text" class="form-control mb-2 mb-md-0 selectelement " data-kt-repeater="select2" data-control="select2" name="kt_docs_repeater_op['.$contador.'][kt_docs_repeater_nested_inner]['.$i.'][elemento]">
																												<option value="">Selecione ou crie o elemento</option>';
																												
																													foreach($array_elementos as $ele){
																														(($ele['EQ_ID'] == $row4['AE_ELEMENTO_ID']) ? $sel = 'selected' : $sel = '' );
																														echo '<option value="'.$ele['EQ_ID'].'" '.$sel.'>'.$ele['EQ_SIGLA'].' - '.$ele['EQ_NOME'].'</option>';
																													}

																												echo '</select>
																											</div>
																											<div class="col-md-2">
																												<input type="number" class="form-control mb-2 mb-md-0 garantia" min="0" name="kt_docs_repeater_op['.$contador.'][kt_docs_repeater_nested_inner]['.$i.'][garantia]" placeholder="Insira a garantia" value="'.$row4['AE_GARANTIA'].'">
																											</div>
																											<div class="col-md-2">
																												<button class="border border-secondary btn-sm btn btn-secondary btn-abrirmodal-ele" data-bs-toggle="modal" data-bs-target="#kt_modal_elemento" type="button">
																													Novo Elemento
																												</button>
																											</div>
																											<div class="col-md-2">
																												<button class="border border-secondary btn btn-sm btn-flex btn-light-danger" data-repeater-delete="" type="button">
																													<i class="fa-solid fa-trash-can"></i> Apagar Elemento
																												</button>
																											</div>
																										</div>
																									</div>';
																								}
																							echo '</div>
																							<button class="btn btn-sm btn-flex btn-light-primary" data-repeater-create="" type="button">
																								<i class="ki-duotone ki-plus fs-5"></i>
																								Garantia
																							</button>
																						</div>
																					<hr style="border-top: 8px solid #009ef7;border-radius: 5px;">
																				</div>';																			
																			}
																			$contador++;
																		?>

																	</div>
																</div>
																<!--end::Form group-->

																<!--begin::Form group-->
																<div class="form-group mb-8" style="margin-left:6px">
																	<a href="javascript:;" data-repeater-create class="btn btn-flex btn-light-primary">
																		<i class="ki-duotone ki-plus fs-3"></i>
																		Ordem de Produção
																	</a>
																</div>
																<!--end::Form group-->
															</div>
															<!--end::Repeater-->
															<br><br>
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<button id="btn-salvar" type="submit" class="btn btn-success  btn-salvar">
																		<span class="indicator-label">
																			Enviar Análise
																		</span>
																		<span class="indicator-progress">
																			Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
																		</span>
																	</button>
																</div>
															</div>				
														<!--  --------------------------------------- FIM DO FORM ------------------------------------>					
														</form>
													</div>

													<div class="modal fade" tabindex="-1" id="kt_modal_elemento">
														<div class="modal-dialog">
															<div class="modal-content">
																<div class="modal-header">
																	<h3 class="modal-title">Criar um novo Elemento</h3>
																	<!--begin::Close-->
																	<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
																		<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
																	</div>
																	<!--end::Close-->
																</div>
																<div class="modal-body">
																	<input type="text" class="form-control mb-5" id="cadsigla" placeholder="Sigla"/>
																	<input type="text" class="form-control mb-5" id="cadelemento" placeholder="Descrição do Elemento"/>
																	<div class="row mb-3">
																		<div class="col">
																			<select type="text" id="cadtipo" class="form-control">
																				<option value="DEFAULT">NORMAL</option>
																				<option value="MP">METAL PESADO</option>
																			</select>
																		</div>
																		<div class="col">
																			<input type="text" class="form-control" id="cadvma" name="cadvma" placeholder="VMA">
																		</div>
																	</div>
																</div>
																<div class="modal-footer">
																	<button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
																	<button type="submit" class="btn btn-primary btn-cadastrar-elemento" id="btn-cadastrar-elemento">
																		<span class="indicator-label">
																			Cadastrar
																		</span>
																		<span class="indicator-progress">
																			Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
																		</span>
																	</button>
																</div>
															</div>
														</div>
													</div>
													
												</div>
												<!--end::Body-->
											</div>
											<!--end::Engage widget 10-->
										</div>
										<!--end::Col-->
									</div>
									<!--end::Row-->
								</div>
								<!--end::Content container-->
							</div>
						</div>
						<!--end::Content wrapper-->
						<!--begin::Footer-->
						<?php require $_SERVER['DOCUMENT_ROOT']."/menu/footer.php"; ?>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Drawers-->
		<?php require $_SERVER['DOCUMENT_ROOT']."/menu/footer-js.php"; ?>
		<script src="<?php echo  $_SESSION['URL']?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
		<script src="<?php echo  $_SESSION['URL']?>/assets/plugins/custom/formrepeater/formrepeater.bundle.js"></script>
		<script src="<?php echo  $_SESSION['URL']?>/assets/js/flatpicker-pt.js"></script>
	</body>
	<!--end::Body-->
</html>
<script>


$('#kt_docs_repeater_nested').repeater({
    repeaters: [{
        selector: '.inner-repeater',
        show: function () {
            $(this).slideDown();
			$('[data-kt-repeater="select2"]').select2({
			});
			
			$(".maskdata").flatpickr({
				dateFormat: "d/m/Y",
				locale: "pt"
			});
        },

        hide: function (deleteElement) {
            $(this).slideUp(deleteElement);
        }
    }],

    show: function () {
        $(this).slideDown();
		$('[data-kt-repeater="select2"]').select2({
		});
		
		$(".maskdata").flatpickr({
			dateFormat: "d/m/Y",
			locale: "pt"
		});
    },

    hide: function (deleteElement) {
        $(this).slideUp(deleteElement);
    }
	
});

$(document).ready(function() {
	
	$(".maskdata").flatpickr({
		dateFormat: "d/m/Y",
		locale: "pt",
	
	});
});

$(document).on('click','#btnSalvarLab',function(){
	$.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "cadastrar-lab", lab: $('#labnovo').val()},
		success: function (response) {
			var result = JSON.parse(response);
			if(result == '1'){
				toastr["success"]("Laboratório criado com sucesso.");
				carregalab();
				$('#labnovo').val("");
				$('#modal_lab').modal('hide'); 
			}else{
				toastr["error"]("Ocorreu um erro. "+result);
			}
		}
	});
});




$(document).on('click','.btn-abrirmodal-ele',function(){
	$('#cadsigla').val("");
	$('#cadelemento').val("");
	$('#cadvma').val("");
});


const btncadastrarelemento = document.getElementById("btn-cadastrar-elemento");
$(document).on('click','.btn-cadastrar-elemento',function(){
	if($('#cadsigla').val() != "" && $('#cadelemento').val() != ""){ 
		
		btncadastrarelemento.setAttribute("data-kt-indicator", "on");
		btncadastrarelemento.disabled = true;

		$.post({
			url: "handle.php", 
			type: "POST", // HTTP verb
			data: {action: "cadastrar-elemento", sigla: $('#cadsigla').val(),  elemento: $('#cadelemento').val(), tipo: $('#cadtipo').val(), vma: $('#cadvma').val()},
			success: function (response) {
				
				var result = JSON.parse(response);
				if(result != '2'){
					toastr["success"]("Elemento Cadastrado.");
					$('#kt_modal_elemento').modal('hide'); 
					
					console.log(result)
					
					var selects = document.querySelectorAll('.selectelement');
					// recarrega os options com novos elementos
					selects.forEach(function(select) {
						console.log(select.value)
						if(select.selectedIndex == ""){
							if ($(select).hasClass('select2-hidden-accessible')) {
								$(select).select2('destroy');
							}
							$(select).empty();
							$(select).append(result);
							$(select).select2();
						    //$(select).trigger('change');
						}else{
							var selecionadoID = select.value;
							if ($(select).hasClass('select2-hidden-accessible')) {
								$(select).select2('destroy');
							}
							$(select).empty();
							$(select).append(result);
							$(select).select2();
						    $(select).val(selecionadoID).trigger('change');
						}
					});

				}else{
					toastr["error"]("Ocorreu um erro. "+result);
				}
				btncadastrarelemento.removeAttribute("data-kt-indicator");
				btncadastrarelemento.disabled = false;
			}
		});
	}else{
		toastr["error"]("Preencha todos os campos. ");
	}
});


const varias_op = document.querySelectorAll('.numero_op');
const form = document.getElementById("form");
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            "ident_quali": {validators: {notEmpty: {message: "Campo obrigatório."}}},
			"selectlab": {validators: {notEmpty: {message: "Campo obrigatório."}}},
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: ".fv-row",
                eleInvalidClass: "",
                eleValidClass: ""
            })
        }
    }
);
varias_op.forEach(function(campo, index) {
    var op = "kt_docs_repeater_op[" + index + "][op]"; // Supondo que o índice seja numérico
    validator.addField(op, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	var prod = "kt_docs_repeater_op[" + index + "][produto]"; // Supondo que o índice seja numérico
    validator.addField(prod, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	var desc = "kt_docs_repeater_op[" + index + "][desc]"; // Supondo que o índice seja numérico
    validator.addField(desc, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	// var cliente = "kt_docs_repeater_op[" + index + "][cliente]"; // Supondo que o índice seja numérico
    // validator.addField(cliente, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	var lote = "kt_docs_repeater_op[" + index + "][lote]"; // Supondo que o índice seja numérico
    validator.addField(lote, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	
	var qtd = "kt_docs_repeater_op[" + index + "][qtd]"; // Supondo que o índice seja numérico
    validator.addField(qtd, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	var datafab = "kt_docs_repeater_op[" + index + "][datafab]"; // Supondo que o índice seja numérico
    validator.addField(datafab, {validators: {notEmpty: {message: "Campo obrigatório."}}});
	var mapa = "kt_docs_repeater_op[" + index + "][mapa]"; // Supondo que o índice seja numérico
    validator.addField(mapa, {validators: {notEmpty: {message: "Campo obrigatório."}}});

	var tipoop = "kt_docs_repeater_op[" + index + "][tipo_op]"; // Supondo que o índice seja numérico
    validator.addField(tipoop, {validators: {notEmpty: {message: "Campo obrigatório."}}});
});





// Submit button handler
const submitButton = document.getElementById("btn-salvar");
submitButton.addEventListener("click", function (e) {
    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log("validated!");

            if (status == "Valid") {
                // Show loading indication
                submitButton.setAttribute("data-kt-indicator", "on");

                // Disable button to avoid multiple click
                submitButton.disabled = true;

                // Simulate form submission. For more info check the plugin"s official documentation: https://sweetalert2.github.io/
                setTimeout(function () {
                    // Remove loading indication
					// dados da OP. 
					const numero_op = document.querySelectorAll('.numero_op');
					const produto_op = document.querySelectorAll('.produto_op');
					const desc_op = document.querySelectorAll('.desc_op');
					const cliente_op = document.querySelectorAll('.cliente_op');
					const lote_op = document.querySelectorAll('.lote_op');
					const nota_op = document.querySelectorAll('.nota_op');
					const qtd_op = document.querySelectorAll('.qtd_op');
					const fab_op = document.querySelectorAll('.fab_op');
					const mapa_op = document.querySelectorAll('.mapa_op');
					const obs_op = document.querySelectorAll('.obs_op');
					const checkmetal = document.querySelectorAll(".metais");
					const validade = document.querySelectorAll(".validade_op");
					const idop = document.querySelectorAll(".idop");
					const fornecedor = document.querySelectorAll(".fornecedor_op");
					const tipoop = document.querySelectorAll(".tipo_op");

					const numero_op_val = [];
					const elemento_val = [];

					for (var i = 0; i < numero_op.length; i++) {
						var valor = numero_op[i].value;
						var valor2 = produto_op[i].value;
						var valor3 = desc_op[i].value;
						var valor4 = cliente_op[i].value;
						var valor5 = lote_op[i].value;
						var valor6 = nota_op[i].value;
						var valor7 = qtd_op[i].value;
						var valor8 = fab_op[i].value;
						var valor9 = mapa_op[i].value;
						var valor10 = obs_op[i].value;
						var valor12 = validade[i].value;
						var valor13 = idop[i].value;
						var valor14 = fornecedor[i].value;
						var valor15 = tipoop[i].value;
						if (checkmetal[i].checked) {
							var valor11 = 1;
						} else {
							var valor11 = 0;
						}
						var position = i;

						numero_op_val.push(i+'|'+valor+'|'+valor2+'|'+valor3+'|'+valor4+'|'+valor5+'|'+valor6+'|'+valor7+'|'+valor8+'|'+valor9+'|'+valor10+'|'+valor11+'|'+valor12+'|'+valor13+'|'+valor14+'|'+valor15);

						// dados da garantia.
						const selectelement = document.querySelectorAll('.selectelement');
						const garantia = document.querySelectorAll('.garantia');

						for (var j = 0; j < selectelement.length; j++) {
							var valorj = document.getElementsByName("kt_docs_repeater_op["+i+"][kt_docs_repeater_nested_inner]["+j+"][elemento]")[0];
							var valorj2 = document.getElementsByName("kt_docs_repeater_op["+i+"][kt_docs_repeater_nested_inner]["+j+"][garantia]")[0];

							if(valorj){
								elemento_val.push(position+'|'+valorj.value+'|'+valorj.options[valorj.selectedIndex].text+'|'+valorj2.value);
							}
						}
					}
					var check;
					var checkboxElement = document.getElementById("checkurgente");
					if (checkboxElement.checked) {
						check = 1;
					} else {
						check = 0;
					}

					var checkcontra;
					var checkboxElement = document.getElementById("checkcontra");
					if (checkboxElement.checked) {
						checkcontra = 1;
					} else {
						checkcontra = 0;
					}
					
					$.post({
						url: "handle.php", // the resource where youre request will go throw
						type: "POST", // HTTP verb
						data: {action: "editar-analise", idcarta: $('#idcarta').val(), ident_quali: $('#ident_quali').val(), selectlab: $('#selectlab').val(), check: check, checkcontra: checkcontra,
						obs_quali: $('#obs_quali').val(), op: numero_op_val, garantia: elemento_val, tipo: $('#tipo').val()}, 
						success: function (response) {
							if(response == '1'){
								
								Swal.fire({
									text: "Análise Cadastrada com sucesso.",
									icon: "success",
									timer: 2000,
								});
								setTimeout(function () {
									window.location.href = "analysis";
								}, 1000);
								
							}else if(response == '2'){
								Swal.fire({
									text: "Erro ao tentar enviar análise. Contate um administrador.",
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								});
							}else if(response == '3'){
								Swal.fire({
									text: "Essa solicitação mudou de status e não pode mais ser alterada.",
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								});
								setTimeout(function () {
									window.location.href = "analysis";
								}, 1000);
							}else{
								Swal.fire({
									text: "Erro ao tentar enviar análise. "+response,
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								});
							}
							submitButton.removeAttribute("data-kt-indicator");
							// Enable button
							submitButton.disabled = false;
						}
					});
                }, 2000);
            }else{
				document.getElementById("form").scrollIntoView();
			}
        });
    }
});


 

</script>
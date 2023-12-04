<?php require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";

if($_SESSION['USUTIPO'] == 'QUALIDADE'){ 
	header('Location: /login'); 
}

require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();

$identquali = "";
$urgente = "";
$obsquali = "";
$tipo = "";
$data = "";

$query2 = $Conexao->query("SELECT CA_ID, CA_STATUS, CA_SOLICITANTE, CA_TIPO, CA_IDENTIFICACAO_QUALI, CA_IDENTIFICACAO_LAB, CA_LABORATORIO, CA_URGENCIA, CA_OBS_LAB, DATE_FORMAT(CA_DATA, '%d/%m/%Y') AS CA_DATA, CA_OBS_QUALI
FROM QUALIDADE_CARTA 
WHERE CA_ID = ".$_GET['id']);
$row = $query2->fetch();
if(!empty($row)){
	$identquali = $row['CA_IDENTIFICACAO_QUALI'];
	$urgente = $row['CA_URGENCIA'];
	$obsquali = $row['CA_OBS_QUALI'];
	$tipo = $row['CA_TIPO'];
	$data = $row['CA_DATA'];
}

$query3 = $Conexao->query("SELECT MA_ID, MA_METODO, MA_USUARIO, MA_DATA
FROM METODO_ANALISE");
while($row3 = $query3->fetch()){ 
	$array_metodo[] = $row3;
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
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
													<!--begin::Wrapper-->
													<div class="mb-10">
														<!--begin::Title-->
														<h1 class="anchor fw-bold mb-5">
                                                            Analisar Amostra.
                                                        </h1>
                                                        <form class="row g-3" id="form" autocomplete="off">
															<input type="hidden" id="idct" value="<?php echo $_GET['id']; ?>" />
															<div class="form-group row mb-5 fv-row">
																<div class="col-md-6">
																	<label for="identquali" class="form-label">Identificação</label>
																	<input type="text" class="form-control" id="identquali" name="identquali" placeholder="" value="<?php echo $identquali ?>" disabled>
																</div>
																<!-- <div class="col-md-2">
																	<label for="tipo" class="form-label">Tipo</label>
																	<input type="text" class="form-control" id="tipo" name="tipo"  value="<?php //echo $tipo == 'PA' ? 'Produto Acabado' : 'Materia Prima' ?>" disabled>
																</div> -->
																<div class="col-md-2">
																	<label for="data" class="form-label">Data</label>
																	<input type="text" class="form-control maskdata" id="data" name="data"  value="<?php echo $data?>" disabled>
																</div>
																<div class="col-md-2">
																	<label for="urgente" class="form-label">Urgente</label>
																	<input type="text" class="form-control" id="urgente" name="urgente"  value="<?php echo $urgente == 1 ? 'URGENTE' : 'Não' ?>" disabled>
																</div>
															</div>
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<label for="obsquali" class="form-label">Observação da Qualidade</label>
																	<input type="text" class="form-control" id="obsquali" value="<?php echo $obsquali; ?>" disabled>
																</div>
															</div>
															<hr>
															<h5>Dados a serem analisados<h5>
															<div class="form-group row mb-5 fv-row">
																<div class="col-md-3">
																	<label class="form-label required ">Identificação do Laboratório</label>
																	<input type="text" class="form-control mb-2 mb-md-0" name="identlab" id="identlab" placeholder="Identificação da carta." maxlength="450" />
																</div>
																<div class="col-md-9">
																	<label class="form-label">Observação</label>
																	<input type="text" class="form-control mb-2 mb-md-0" name="obslab" id="obslab" placeholder="Se for necessário adicione uma observação."  maxlength="500"/>
																</div>
																
															</div>
															<!-- ------------------------------------------- ------------------------------------------------------- --------------------------------------------- -------------------->
															<h5>Informação da Ordem de Produção<h5>
															<div class="accordion accordion-icon-collapse" id="kt_accordion_3">
																<!--begin::Item-->
																<?php 
																$contador = 0;
																$query3 = $Conexao->query("SELECT PO_ID, PO_ID_CARTA, PO_NUMERO, PO_PRODUTO, PO_PROD_DESC, PO_LOTE,	PO_QTD,	 DATE_FORMAT(PO_DATA_FAB, '%d/%m/%Y') AS PO_DATA_FAB , 
																PO_REG_MAPA, DATE_FORMAT(PO_DATA_VALIDADE, '%d/%m/%Y') AS PO_DATA_VALIDADE, PO_TIPO,
																PO_NOTA, PO_OBS_LAB, PO_OBS_QUALI, PO_ID_LAB, PO_ID_SOLICITANTE, PO_DATA_CRIADO, PO_OBS_LAUDO, PO_CLIENTE, PO_METAIS_PESADOS, PO_DATA
																FROM QUALIDADE_PRODUTO 
																WHERE PO_ID_CARTA = ".$_GET['id']);
																while($row2 = $query3->fetch()){

																	$contador++;
																	$tipo = null;
																	if($row2['PO_TIPO'] == 'PA'){
																		$tipo = 'Produto Acabado';
																	}else if($row2['PO_TIPO'] == 'MP'){
																		$tipo = 'Matéria Prima';
																	}else if($row2['PO_TIPO'] == 'MICRO'){
																		$tipo = 'Micronutriente';
																	}
																	echo '<div class="mb-5">
																		<!--begin::Header-->
																		<div class="accordion-header py-3 d-flex collapsed" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_item_'.$contador.'">
																			<span class="accordion-icon">
																				<i class="ki-duotone ki-plus-square fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
																				<i class="ki-duotone ki-minus-square fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
																			</span>
																			<h3 class="fs-4 fw-semibold mb-0 ms-4">OP: '.$row2['PO_NUMERO'].'</h3>
																		</div>
																		<!--end::Header-->

																		<!--begin::Body-->
																		<div id="kt_accordion_3_item_'.$contador.'" class="collapse fs-6 ps-10" data-bs-parent="#kt_accordion_3">
																			<input type="hidden" class="idprod" value="'.$row2['PO_ID'].'" name="idprod"/>
																			<div class="form-group row mb-5">
																				<div class="col-md-3">
																					<label for="" class="form-label">Produto</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_PRODUTO'].'" disabled>
																				</div>
																				<div class="col-md-4">
																					<label for="" class="form-label">Descrição</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_PROD_DESC'].'" disabled>
																				</div>
																				<div class="col-md-2">
																					<label for="" class="form-label">Lote</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_LOTE'].'" disabled>
																				</div>
																				<div class="col-md-2">
																					<label for="" class="form-label">Nota</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_NOTA'].'" disabled>
																				</div>
																				<div class="col-md-1">
																					<label for="" class="form-label">Quantidade</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_QTD'].'" disabled>
																				</div>
																			</div>
																			<div class="form-group row mb-5">
																				<div class="col-md-2">
																					<label for="" class="form-label">Fabricação</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_DATA_FAB'].'" disabled>
																				</div>
																				<div class="col-md-2">
																					<label for="" class="form-label">Mapa</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_REG_MAPA'].'" disabled>
																				</div>
																				<div class="col-md-2">
																					<label for="" class="form-label">Validade</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_DATA_VALIDADE'].'" disabled>
																				</div>
																				<div class="col-md-4">
																					<label for="" class="form-label">Observação da Qualidade</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$row2['PO_OBS_QUALI'].'" disabled>
																				</div>
																				<div class="col-md-2">
																					<label for="" class="form-label">Tipo</label>
																					<input type="text" class="form-control" id="" name="" placeholder="" value="'.$tipo.'" disabled>
																				</div>
																			</div>
																			<div class="form-group row mb-5">
																				<div class="col-md-2">
																					<label for="" class="form-label">Analisar Metais Pesados</label>
																					<input type="text" class="form-control" id=""  name="" placeholder="" value="'.($row2['PO_METAIS_PESADOS']  == 0 ? 'NÃO' : 'SIM').'" style="'.($row2['PO_METAIS_PESADOS']  == 0 ? 'color:#009ef7' : 'color:#f1416c').'" disabled>
																				</div>
																				<div class="col-md-5">
																					<label for="" class="form-label">Observação</label>
																					<input type="text" class="form-control obsproduto" id="" name="obsproduto" value="'.$row2['PO_OBS_LAB'].'" placeholder="Insira uma observação para a qualidade.">
																				</div>
																				<div class="col-md-5">
																					<label for="" class="form-label">Essa observação vai sair no laudo.</label>
																					<input type="text" class="form-control obslaudo" id="" name="obslaudo" value="'.$row2['PO_OBS_LAUDO'].'" placeholder="Insira uma observação para sair no Laudo.">
																				</div>
																			</div>
																			<span class="badge badge-light-primary" style="font-size:16px">Garantias</span>';

																			$query4 = $Conexao->query("SELECT AE_ID, AE_PRODUTO_ID, AE_ELEMENTO_ID, AE_ELEMENTO, AE_GARANTIA, AE_VMA, AE_RESULTADO, AE_METODO, EQ_VMA, AE_UNIDADE, AE_LQ, AE_VR, AE_ENSAIO
																			FROM QUALIDADE_ANALISE LEFT JOIN QUALIDADE_ELEMENTOS_QUIMICOS
																			ON AE_ELEMENTO_ID = EQ_ID
																			WHERE AE_PRODUTO_ID = ".$row2['PO_ID']);
																			while($row4 = $query4->fetch()){
																				echo '<div class="form-group row mb-5">
																					<div class="col-md-2">
																						<input type="hidden" class="inputhidden" name="inputhidden" value="'.$row2['PO_ID'].'" />
																						<input type="text" class="form-control elemento" id="" name="elemento" placeholder="" value="'.$row4['AE_ELEMENTO'].'" disabled>
																					</div>
																					<div class="col-md-1">
																						<input type="text" class="form-control garantia" id="" name="garantia" placeholder="" value="'.number_format($row4['AE_GARANTIA'], 2, ',', '.').'" disabled>
																					</div>
																					<div class="col-md-1 fv-row">
																						<input type="number" class="form-control result" id="'.$row2['PO_ID'].'|'.$row4['AE_ID'].'" min="0" name="result" placeholder="Resultado" value="'.$row4['AE_RESULTADO'].'" >
																					</div>
																					<div class="col-md-1 fv-row">
																						<input type="text" class="form-control unidade" id="'.$row2['PO_ID'].'|'.$row4['AE_ID'].'"  name="unidade" placeholder="Unidade" value="'.$row4['AE_UNIDADE'].'" >
																					</div>
																					<div class="col-md-1">
																						<input type="text" class="form-control lq" id="'.$row2['PO_ID'].'|'.$row4['AE_ID'].'"  name="lq" placeholder="L.Q" value="'.$row4['AE_LQ'].'" >
																					</div>
																					<div class="col-md-1">
																						<input type="text" class="form-control vrr" id="'.$row2['PO_ID'].'|'.$row4['AE_ID'].'"  name="vrr" placeholder="VR" value="'.$row4['AE_VR'].'" >
																					</div>';
																						if($row2['PO_METAIS_PESADOS'] == '1'){
																							echo '<div class="col-md-1">
																							<input type="text" class="form-control vma" id="" name="vma" placeholder="VMA" value="'.(($row4['AE_VMA'] == "") ? $row4['EQ_VMA'] : $row4['AE_VMA']).'" disabled>
																						</div>';
																					}
																					echo '<div class="col-md-3">
																						<select class="form-select metodo" data-control="select2" name="metodo" data-placeholder="Selecione o método de análise ou crie.">
																							<option></option>';
																							foreach($array_metodo as $met){
																								echo '<option value="'.$met['MA_ID'].'">'.$met['MA_METODO'].'</option>';
																							}
																					echo '</select>
																					</div>
																					<div class="col-md-1 fv-row">
																						<input type="text" class="form-control ensaio" id="'.$row2['PO_ID'].'|'.$row4['AE_ID'].'"  name="ensaio" placeholder="Ensaio" value="'.$row4['AE_ENSAIO'].'" >
																					</div>
																				</div>';
																			}
																			echo '<div class="divmais'.$row2['PO_ID'].'"></div>';
																			// -------------------------------------- adicionar novo elemento ----------------------------------------------
																			//echo '<h6>Se algum elemento a mais for encontrado, informe manualmente.</h6>
																			// <div class="form-group row mb-5">
																			// 	<div class="col-md-1">
																			// 		<input type="text" class="form-control novo_sigla'.$row2['PO_ID'].'" id="" name="novo_sigla" placeholder="Sigla" value="" >
																			// 	</div>
																			// 	<div class="col-md-2">
																			// 		<input type="text" class="form-control novo_elemento'.$row2['PO_ID'].'" id="" name="novo_elemento" placeholder="Elemento" value="" >
																			// 	</div>
																			// 	<div class="col-md-1">
																			// 		<input type="number" class="form-control novo_resultado'.$row2['PO_ID'].'" id="" name="novo_resultado" placeholder="Resultado" value="" >
																			// 	</div>
																			// 	<div class="col-md-1">
																			// 		<input type="text" class="form-control novo_unidade'.$row2['PO_ID'].'" id="" name="novo_unidade" placeholder="Unidade" value="" >
																			// 	</div>
																			// 	<div class="col-md-1">
																			// 		<input type="text" class="form-control novo_lq'.$row2['PO_ID'].'" id="" name="novo_lq" placeholder="L.Q" value="" >
																			// 	</div>
																			// 	<div class="col-md-1">
																			// 		<input type="text" class="form-control novo_vr'.$row2['PO_ID'].'" id="" name="novo_vr" placeholder="VR" value="" >
																			// 	</div>
																			// 	<div class="col-md-3">
																			// 		<select class="form-select novo_metodo novo_metodo'.$row2['PO_ID'].'" data-control="select2" name="novo_metodo" data-placeholder="Selecione o método de análise ou crie.">
																			// 			<option></option>';
																			// 			foreach($array_metodo as $met){
																			// 				echo '<option value="'.$met['MA_ID'].'">'.$met['MA_METODO'].'</option>';
																			// 			}
																			// 		echo '</select>
																			// 	</div>
																			// 	<div class="col-md-1">
																			// 		<input type="text" class="form-control novo_ensaio'.$row2['PO_ID'].'" id="" name="novo_unidade" placeholder="Ensaio" value="" >
																			// 	</div>
																			// 	<div class="col-md-3">
																			// 		<button id="'.$row2['PO_ID'].'" type="button" class="btn btn-info btn-novoel"  name="btn-novoel">
																			// 			<span class="indicator-label">
																			// 				+ Elemento
																			// 			</span>
																			// 			<span class="indicator-progress">
																			// 				Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
																			// 			</span>
																			// 		</button>
																			// 	</div>
																			// </div>';
																			echo'<hr>
																		</div>
																		<!--end::Body-->
																	</div>
																	<!--end::Item-->';
																}
																?>

															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<button id="btn-salvar" type="submit" class="btn btn-primary  btn-salvar">
																		<span class="indicator-label">
																			Enviar Analisar
																		</span>
																		<span class="indicator-progress">
																			Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
																		</span>
																	</button>
																</div>
															</div>
														</form>
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
							<!--end::Content-->
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


	</body>
	<!--end::Body-->
</html>

<script>

$(document).ready(function() {

	Inputmask({"mask" : '99/99/9999'}).mask(".maskdata");
	$(".metodo").select2({
		tags: true
	});
	$(".novo_metodo").select2({
		tags: true
	});

	Inputmask({
		"mask" : "99/99/9999"
	}).mask(".ensaio");
});

 
const form = document.getElementById("form");

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            "identlab": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
                    }
                }
            },
			"result": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
                    },
                }
            },
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
					const obsproduto_op = document.getElementsByName('obsproduto');
					const obslaudo_op = document.getElementsByName('obslaudo');
					const idprod_op = document.getElementsByName('idprod');

					const op = [];
					const elementos = [];

					for (var i = 0; i < idprod_op.length; i++) {
						var obsprod = obsproduto_op[i].value;
						var obslaudo = obslaudo_op[i].value;
						var idprod = idprod_op[i].value;

						op.push(idprod+'|'+obsprod+'|'+obslaudo);					
					}
					
					const inputhidden = document.getElementsByName('inputhidden');
					const elemento = document.getElementsByName('elemento');
					const result = document.getElementsByName('result');
					const unidade = document.getElementsByName('unidade');
					const lq = document.getElementsByName('lq');
					const vrr = document.getElementsByName('vrr');
					const ensaio = document.getElementsByName('ensaio');
					const metodo = document.getElementsByName('metodo');

					for (var j = 0; j < elemento.length; j++) {
					
						var select = metodo[j];
						var selectedIndex = select.selectedIndex;
						var selectedOption = select.options[selectedIndex];
						var textoSelecionado = selectedOption.text;
						elementos.push(result[j].id+'|'+elemento[j].value+'|'+result[j].value+'|'+textoSelecionado+'|'+unidade[j].value+'|'+lq[j].value+'|'+vrr[j].value+'|'+ensaio[j].value);
					}
					$.post({
						url: "handle.php", // the resource where youre request will go throw
						type: "POST", // HTTP verb
						data: {action: "cadastrar-analise", 
							idct: $('#idct').val(),
							identlab: $('#identlab').val(), obslab: $('#obslab').val(), 
							op: op, elementos: elementos
						}, 
						success: function (response) {
							if(response == '1'){
								submitButton.removeAttribute("data-kt-indicator");
								// Enable button
								submitButton.disabled = false;
								Swal.fire({
									text: "Análise realizada!",
									icon: "success",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								}).then((result) => {
  									if (result.isConfirmed) {
										window.location.href = "analysis";
									}
								})
								
							}else{
								toastr["error"]("Ocorreu um erro. "+response);
							}
						}
					});
                }, 2000);
            }
        });
    }
});



var idbtn;
$(document).on('click','.btn-novoel',function(){
    idbtn = $(this).attr('id');
	const elemento = $('.novo_elemento'+idbtn).val();
	const sigla =  $('.novo_sigla'+idbtn).val();
	const resultado = $('.novo_resultado'+idbtn).val();
	const metodo = $('.novo_metodo'+idbtn).find(":selected").text();
	console.log(resultado)
    $.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "adicionar-linha", id: idbtn, elemento: elemento, sigla: sigla, resultado: resultado, metodo: metodo},
		success: function (response) {
			var result = JSON.parse(response);
			if(result != '2'){
                $(".divmais"+idbtn).append(result);
				$('.novo_elemento'+idbtn).val("");
				$('.novo_sigla'+idbtn).val("");
				$('.novo_resultado'+idbtn).val("");
				$('.novo_metodo'+idbtn).val("");
			}else{
				toastr["error"]("Ocorreu um erro. "+result);
			}
		}
	});
});

$(document).on('click','.btn-remover',function(){
    // Remover a div-pai do botão
    $(this).closest('.form-group').remove();
  });
</script>
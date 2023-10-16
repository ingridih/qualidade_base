<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
if ($_SESSION['USUADMIN'] != '1') {
    header('Location: /login'); die;
}
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();


$nome = null;
$email = null;
$empresa_id = null;
$tipo = null;
$telefone = null;
$doc = null;
$idusu = null;

if(isset($_GET['id'])){
	$idusu = base64_decode($_GET['id']);

	$query = $Conexao->query("SELECT USU_NOME, USU_EMAIL, USU_ATIVO, USU_EMPRESA_NOME, USU_TIPO, USU_ID, USU_DOC, USU_TELEFONE, USU_EMPRESAID
		FROM QUALIDADE_USUARIO_LOGIN
		WHERE USU_ID = ".$idusu);
	$row = $query->fetch();
	if(!empty($row)){
		$nome = $row['USU_NOME'];
		$email = $row['USU_EMAIL'];
		$empresa_id = $row['USU_EMPRESAID'];
		$tipo = $row['USU_TIPO'];
		$telefone = $row['USU_TELEFONE'];
		$doc = $row['USU_DOC'];
	}
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
														<h1 class="anchor fw-bold mb-5">
                                                            <?php echo ($idusu == null) ? 'Criar um novo usuário.' : 'Editar Usuário.'?>
                                                        </h1>
                                                        <form class="row g-3" id="form">
															<input type="hidden" id="usuarioid" value="<?php echo $idusu ?>" />
															<input type="hidden" id="empresa_id" value="<?php echo $empresa_id ?>" />
															<div class="form-group row mb-5 fv-row">
																<div class="col-md-6">
																	<label for="nome" class="form-label">Nome Completo</label>
																	<input type="text" class="form-control" id="nome" name="nome" placeholder="Insira o nome do usuário" value="<?php echo $nome ?>">
																</div>
																<div class="col-md-6">
																	<label for="email" class="form-label">Email</label>
																	<input type="email" class="form-control" id="email" name="email" placeholder="Insira o email de acesso." value="<?php echo $email ?>" <?php echo ($idusu != null) ? 'disabled' : '' ?>>
																</div>
															</div>
															<div class="form-group row mb-5">
																<div class="col-md-3">
																	<label for="nome" class="form-label">Contato</label>
																	<input type="text" class="form-control" id="telefone" placeholder="Insira um numero de contato." value="<?php echo $telefone ?>">
																</div>
																<div class="col-md-3">
																	<label for="doc" class="form-label">Documento</label>
																	<input type="text" class="form-control" id="doc" placeholder="Insira um documento" value="<?php echo $doc ?>">
																</div>
															</div>
															<div class="form-group row mb-5">
																<div class="col-md-3">
																	<label for="tipo" class="form-label">Tipo</label>
																	<select class="form-control" id="tipo">
																		<option <?php echo (($tipo == 'QUALIDADE') ? 'selected' : '') ?> value="QUALIDADE">Interno (Qualidade)</option>
																		<option <?php echo (($tipo == 'LABORATORIO') ? 'selected' : '') ?> value="LABORATORIO">Externo (Laboratório)</option>
																	</select>
																</div>
																
																<div class="col-md-4 divlab fv-row" style="<?php echo ($tipo == 'LABORATORIO') ? '' : 'display:none' ?>">
																	<label for="selectlab" class="form-label">Laboratório</label>
																	<select class="form-select" id="selectlab" name="selectlab" data-placeholder="Selecione ou crie um novo Laboratório">
																		<option value="">Selecione um laboratório ou crie...</option>
																	</select>
																</div>
																<div class="col-md-3 divlab" style="display:none">
																	<button type="button" style="margin-top: 27px;" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modal_lab">Cadastrar Laboratório</button>
																</div>
															</div>
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<button id="btn-salvar" type="submit" class="btn btn-primary  btn-salvar">
																		<span class="indicator-label">
																			<?php echo (($idusu == null) ? 'Cadastrar Usuário' : 'Editar Usuário')?>
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
                            <div class="modal fade" tabindex="-1" id="modal_lab">
								<div class="modal-dialog">
									<div class="modal-content">
										<div class="modal-header">
											<h3 class="modal-title">Criar Laboratório</h3>
											<!--begin::Close-->
											<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
												<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
											</div>
											<!--end::Close-->
										</div>

										<div class="modal-body">
											<p>Insira o nome do laboratório que deseja criar.</p>
											<div class="col-md-12">
												<label for="text" class="form-label">Laboratório</label>
												<input type="text" class="form-control" id="labnovo" placeholder="Insira o nome do Laboratório">
											</div>
										</div>

										<div class="modal-footer">
											<button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
											<button type="button" class="btn btn-primary" id="btnSalvarLab">Salvar</button>
										</div>
									</div>
								</div>
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
	</body>
	<!--end::Body-->
</html>
<script>

$(document).ready(function() {
	carregalab();
	Inputmask({
		"mask" : ['999.999.999-99', '99.999.999/9999-99']
	}).mask("#doc");
	Inputmask({
		"mask" : ['(99) 9999-9999', '(99) 99999-9999']
	}).mask("#telefone");

	
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


function carregalab(){
	$.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "busca-lab"},
		success: function (response) {
			var result = JSON.parse(response);
			$('#selectlab').empty();
			$('#selectlab').append(result);
			$('#selectlab').val($('#empresa_id').val());
		}
	});
}

 $(document).on('change','#tipo',function(){
	if($(this).val() == 'LABORATORIO'){
		$(".divlab").show();
		carregalab();
	}else{
		$(".divlab").hide();
	}
 })



const form = document.getElementById("form");

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            "nome": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
                    }
                }
            },
			"email": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
                    },
					emailAddress: {
                        message: 'Email invalido.'
                    },
                }
            },
			"selectlab": {
				validators: {
					callback: {
						message: 'Campo obrigatório.',
						callback: function (input) {
							if ($('#tipo').val() === 'LABORATORIO' && input.value === "") {
								return {valid: false, message: 'Campo Obrigatório.'};
							}else{
								return {valid: true};
							}    
						}
					}
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
					var selectedText = $("#selectlab option:selected").text();

					$.post({
						url: "handle.php", // the resource where youre request will go throw
						type: "POST", // HTTP verb
						data: {action: "cadastrar-colab", nome: $('#nome').val(), email: $('#email').val(), telefone: $('#telefone').val(),
						doc: $('#doc').val(), tipo: $('#tipo').val(), selectlab: $('#selectlab').val(), selectlab_text: selectedText, usuarioid: $('#usuarioid').val()},
						success: function (response) {
							if(response == '1'){
								
								Swal.fire({
									text: "Usuário cadastrado, ele irá receber um email com os acessos.!",
									icon: "success",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								}).then((result) => {
  									if (result.isConfirmed) {
										window.location.href = "user-list";
									}
								})
								
							}else if(response == '2'){
								toastr["error"]("Ocorreu um erro. "+response);
							}else if(response == '3'){
								Swal.fire({
									text: "Esse usuário já possui cadastro.",
									icon: "error",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								});
							}else if(response == '4'){
								Swal.fire({
									text: "Usuário editado com sucesso!",
									icon: "success",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								}).then((result) => {
  									if (result.isConfirmed) {
										window.location.href = "user-list";
									}
								})
							}
							submitButton.removeAttribute("data-kt-indicator");
							// Enable button
							submitButton.disabled = false;
						}
					});
                }, 2000);
            }
        });
    }
});


 

</script>
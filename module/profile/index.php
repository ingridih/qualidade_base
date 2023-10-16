<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();


$nome = null;
$email = null;
$empresa_id = null;
$tipo = null;
$telefone = null;
$doc = null;
$idusu = null;
$empresa  = null;

$query = $Conexao->query("SELECT USU_NOME, USU_EMAIL, USU_ATIVO, USU_EMPRESA_NOME, USU_TIPO, USU_ID, USU_DOC, USU_TELEFONE, USU_EMPRESAID
    FROM QUALIDADE_USUARIO_LOGIN
    WHERE USU_ID = ".$_SESSION['USUID']);
$row = $query->fetch();
if(!empty($row)){
    $nome = $row['USU_NOME'];
    $email = $row['USU_EMAIL'];
    $empresa_id = $row['USU_EMPRESAID'];
    $tipo = $row['USU_TIPO'];
    $telefone = $row['USU_TELEFONE'];
    $doc = $row['USU_DOC'];
	$empresa = $row['USU_EMPRESA_NOME'];
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
                                                            Editar dados Pessoais
                                                        </h1>
                                                        <form class="row g-3" id="form">
                                                            <ul class="nav nav-tabs nav-line-tabs nav-line-tabs-2x mb-5 fs-6">
                                                                <li class="nav-item">
                                                                    <a class="nav-link active" data-bs-toggle="tab" href="#kt_tab_pane_4">Dados Pessoais</a>
                                                                </li>
                                                                <li class="nav-item">
                                                                    <a class="nav-link" data-bs-toggle="tab" href="#kt_tab_pane_5">Alterar Senha</a>
                                                                </li>
                                                            </ul>

                                                            <div class="tab-content" id="myTabContent">
                                                                <div class="tab-pane fade show active" id="kt_tab_pane_4" role="tabpanel">
                                                                    <div class="form-group row mb-5 ">
                                                                        <div class="col-md-6 fv-row">
                                                                            <label for="nome" class="form-label required ">Nome</label>
                                                                            <input type="text" class="form-control" id="nome" name="nome" placeholder="Nome do usuário" value="<?php echo $nome;?>" autocomplete="off">
                                                                        </div>
                                                                        <div class="col-md-6 fv-row">
                                                                            <label for="email" class="form-label required ">Email</label>
                                                                            <input type="text" class="form-control" id="email" name="email" placeholder="Email do usuário" value="<?php echo $email;?>" autocomplete="off" disabled>
                                                                        </div>
                                                                    </div>
                                                                    <div class="form-group row mb-5 ">
                                                                        <div class="col-md-3 fv-row">
                                                                            <label for="telefone" class="form-label required ">Contato</label>
                                                                            <input type="text" class="form-control" id="telefone" name="telefone" placeholder="Contato Telefonico" value="<?php echo $telefone;?>" autocomplete="off">
                                                                        </div>
                                                                        <div class="col-md-3 fv-row">
                                                                            <label for="doc" class="form-label required ">Documento</label>
                                                                            <input type="text" class="form-control" id="doc" name="doc" placeholder="Documento" value="<?php echo $doc;?>" autocomplete="off">
                                                                        </div>
																		<div class="col-md-6 fv-row">
                                                                            <label for="email" class="form-label required ">Empresa</label>
                                                                            <input type="text" class="form-control" id="empresa" name="empresa" placeholder="Empresa pertencente" value="<?php echo $empresa;?>" autocomplete="off" disabled>
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                                <div class="tab-pane fade" id="kt_tab_pane_5" role="tabpanel">

                                                                    <div class="form-group row mb-5 ">

                                                                        <div class="col-md-6 fv-row" data-kt-password-meter="true">
                                                                            <!--begin::Wrapper-->
                                                                            <div class="mb-1">
                                                                                <!--begin::Label-->
                                                                                <label class="form-label fw-semibold fs-6 mb-2">
                                                                                    Nova Senha
                                                                                </label>
                                                                                <!--end::Label-->

                                                                                <!--begin::Input wrapper-->
                                                                                <div class="position-relative mb-3">
                                                                                    <input class="form-control" type="password" placeholder="" name="new_password" id="new_password" autocomplete="off" />

                                                                                    <!--begin::Visibility toggle-->
                                                                                    <span class="btn btn-sm btn-icon position-absolute translate-middle top-50 end-0 me-n2"
                                                                                        data-kt-password-meter-control="visibility">
                                                                                            <i class="ki-duotone ki-eye-slash fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span></i>
                                                                                            <i class="ki-duotone ki-eye d-none fs-1"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
                                                                                    </span>
                                                                                    <!--end::Visibility toggle-->
                                                                                </div>
                                                                                <!--end::Input wrapper-->

                                                                                <!--begin::Highlight meter-->
                                                                                <div class="d-flex align-items-center mb-3" data-kt-password-meter-control="highlight">
                                                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px me-2"></div>
                                                                                    <div class="flex-grow-1 bg-secondary bg-active-success rounded h-5px"></div>
                                                                                </div>
                                                                                <!--end::Highlight meter-->
                                                                            </div>
                                                                            <!--end::Wrapper-->

                                                                            <!--begin::Hint-->
                                                                            <div class="text-muted">
                                                                                Senha com 8 caracteres com letras (maiuscula e minuscula), números e simbolos. 
                                                                            </div>
                                                                            <!--end::Hint-->
                                                                        </div>
                                                                        <div class="col-md-6 fv-row">
                                                                            <label for="confirmsenha" class="form-label required ">Confirmar Senha</label>
                                                                            <input type="password" class="form-control" id="confirmsenha" name="confirmsenha" placeholder="Insira a senha novamente" autocomplete="off">
                                                                        </div>
                                                                    </div>
                                                                </div>
                                                            </div>
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<button id="btn-salvar" type="submit" class="btn btn-primary btn-salvar">
																		<span class="indicator-label">
																			Salvar Dados
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

	Inputmask({
		"mask" : ['999.999.999-99', '99.999.999/9999-99']
	}).mask("#doc");
	Inputmask({
		"mask" : ['(99) 9999-9999', '(99) 99999-9999']
	}).mask("#telefone");

	passwordMeter = KTPasswordMeter.getInstance(form.querySelector('[data-kt-password-meter="true"]'));

	
});




const form = document.getElementById("form");

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
			"confirmsenha": {
				validators: {
					callback: {
						message: 'Por favor, entre com uma senha.',
						callback: function (input) {
							if ($('#new_password').val() != "") {
								if(input.value != $('#new_password').val()){ 
									return {valid: false, message: 'As senhas não conferem.'};
								}else{
									return {valid: true};
								}
							}else{
								return {valid: true};
							}
						}
					}
				}
			},
			'new_password': {
				validators: {
					callback: {
						
						callback: function(input) {
							if (input.value.length > 0) {
								if(validatePassword() == true){
									return {valid: true};
								}else{
									return {valid: false, message: 'Entre com uma senha que atenda aos requisitos.'};
								}
							}
						}
					}
				}
			},
            "nome": {
                validators: {
                    notEmpty: {
                        message: "Campo obrigatório."
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

// Password input validation
var validatePassword = function() {
	console.log(passwordMeter.getScore());
	return (passwordMeter.getScore() === 100);
	
}

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
						data: {action: "alterar-colab", nome: $('#nome').val(), email: $('#email').val(), telefone: $('#telefone').val(),
						doc: $('#doc').val(), novasenha: $('#new_password').val(), confirmsenha: $('#confirmsenha').val()},
						success: function (response) {
							if(response == '1'){
								
								Swal.fire({
									text: "Usuário editado com sucesso",
									icon: "success",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								}).then((result) => {
  									if (result.isConfirmed) {
										window.location.href = "<?php echo $_SESSION['URL'] ?>";
									}
								})
								
							}else if(response == '2'){
								toastr["error"]("Ocorreu um erro. "+response);
							}
							submitButton.removeAttribute("data-kt-indicator");
							// Enable button
							submitButton.disabled = false;
						}
					});
                }, 2000);
            }else{
				Swal.fire({
					text: "Preencha todos os campos obrigatórios.",
					icon: "error",
					buttonsStyling: false,
					confirmButtonText: "Ok",
					customClass: {
						confirmButton: "btn btn-primary"
					}
				});
			}
        });
    }
});


 

</script>
<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";

if (isset($_SESSION['USUID'])) {
    if($_SESSION['USUTIPO'] == 'QUALIDADE'){ 
        header('Location: /login'); 
    }
}else{
    header('Location: /login'); 
}
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();

$logo_anexo = null;
$logo_texto = null;
$topo = null;
$bottom = null;
$assinatura = null;
$id = null;

$query = $Conexao->query("SELECT LC_ID, LC_LOGO_ARQ, LC_LOGO_TEXTO, LC_TOPO, LC_BOTTOM, LC_ASSINATURA, LC_USU
  FROM LAUDO_CONFIGURACAO");
$row = $query->fetch();
if(!empty($row)) {
	$logo_anexo = $row['LC_LOGO_ARQ'];
	$logo_texto = $row['LC_LOGO_TEXTO'];
	$topo = $row['LC_TOPO'];
	$bottom = $row['LC_BOTTOM'];
	$assinatura = $row['LC_ASSINATURA'];
	$id = $row['LC_ID'];
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
														<h1 class="fw-bold mb-5">
                                                            Definir Laudo automático 
															<div class="col-lg-1" style="float: right;">
																<a class="d-block overlay" data-fslightbox="lightbox-basic" href="../../../img/ex.jpg">
																	<!--begin::Image-->
																	<div class="btn btn-icon btn-warning me-2 mb-2 " style="border-radius:50%">
																		<i class="fa-solid fa-question fs-2"></i>     
																	</div>
																	<!--end::Image-->
																</a>
																<!--end::Overlay-->
															</div>
                                                        </h1>
														
                                                        <form class="row g-3" id="form">
															
															<h5><span style="color:#feae48; font-weight:600; font-size:24px">1.</span> Insira o logo em forma de anexo ou insira com de forma manual (Se houver arquivo, ele será considerado e não o texto)</h5>
															
															<div class="image-input image-input-outline image-input-placeholder3 <?php echo (($logo_anexo == "") ? " image-input-empty" : "" )?>" data-kt-image-input="true">
																<div class="image-input image-input-outline <?php echo (($logo_anexo == "") ? " image-input-empty" : "" )?>" data-kt-image-input="true" style="background-image: url(/assets/media/svg/avatars/blank.svg)">
																	<!--begin::Image preview wrapper-->
																	<div class="image-input-wrapper w-150px h-150px" style="background-image: url(/file/config/<?php echo $logo_anexo ?>); background-size: contain; background-position: center; background-repeat: no-repeat;"></div>
																	<!--end::Image preview wrapper-->

																	<!--begin::Edit button-->
																	<label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
																		data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Mudar Logo">
																		<i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

																		<!--begin::Inputs-->
																		<input type="file" name="avatar" id="formFile" accept=".png, .jpg, .jpeg" />
																		<input type="hidden" name="avatar_remove" />
																		<!--end::Inputs-->
																	</label>
																	<!--end::Edit button-->

																	<!--begin::Cancel button-->
																	<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
																	data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancelar">
																		<i class="ki-outline ki-cross fs-3"></i>
																	</span>
																	<!--end::Cancel button-->

																	<!--begin::Remove button-->
																	<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
																	data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remover Logo">
																		<i class="ki-outline ki-cross fs-3"></i>
																	</span>
																	<!--end::Remove button-->
																</div>
															</div>

															<!--end::Image input-->
															<div class="form-group row mb-5 fv-row">
																<div class="col-md-6">
																	<label for="nome" class="form-label">Insira o nome da empresa (Se houver anexo, o anexo será considerado)</label>
																	<input type="text" class="form-control" id="nome" name="nome" placeholder="Insira o nome da Empresa" value="<?php echo $logo_texto ?> ">
																</div> 
															</div>
															
															<div class="mb-5">
																<span style="color:#feae48; font-weight:600; font-size:24px">2.</span>
																<div class="">
																	<label for="kt_docs_quill_basic" class="form-label">Informações sobre o Laboratório (topo)</label>
																	<div id="kt_docs_quill_basic" name="kt_docs_quill_basic" class="ql-container ql-snow topo"><?php echo $topo ?></div>
																</div>
															</div>

															<div class="mb-5">
															<span style="color:#feae48; font-weight:600; font-size:24px">3.</span>
																<div class="">
																	<label for="kt_docs_quill_basic2" class="form-label">Informações finais (bottom)</label>
																	<div id="kt_docs_quill_basic2" name="kt_docs_quill_basic2" class="ql-container ql-snow bottom"><?php echo $bottom ?></div>
																</div>
															</div>

															<div class="form-group row mb-5">
																<span style="color:#feae48; font-weight:600; font-size:24px">4.</span>
																<label for="assinaturaFile" class="form-label">Assinatura que sai no Laudo</label>
																<div class="col-md-6">

																	<div class="image-input image-input-outline image-input-placeholder3 <?php echo (($logo_anexo == "") ? " image-input-empty" : "" )?>" data-kt-image-input="true">
																		<div class="image-input image-input-outline <?php echo (($logo_anexo == "") ? " image-input-empty" : "" )?>" data-kt-image-input="true" style="background-image: url(/assets/media/svg/avatars/blank.svg)">
																			<!--begin::Image preview wrapper-->
																			<div class="image-input-wrapper w-150px h-150px" style="background-image: url(/file/config/<?php echo $assinatura ?>); background-size: contain; background-position: center; background-repeat: no-repeat;"></div>
																			<!--end::Image preview wrapper-->

																			<!--begin::Edit button-->
																			<label class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
																				data-kt-image-input-action="change" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Mudar Logo">
																				<i class="ki-duotone ki-pencil fs-6"><span class="path1"></span><span class="path2"></span></i>

																				<!--begin::Inputs-->
																				<input type="file" name="avatar" id="assinaturaFile" accept=".png, .jpg, .jpeg" />
																				<input type="hidden" name="avatar_remove" />
																				<!--end::Inputs-->
																			</label>
																			<!--end::Edit button-->

																			<!--begin::Cancel button-->
																			<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
																			data-kt-image-input-action="cancel" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Cancelar">
																				<i class="ki-outline ki-cross fs-3"></i>
																			</span>
																			<!--end::Cancel button-->

																			<!--begin::Remove button-->
																			<span class="btn btn-icon btn-circle btn-color-muted btn-active-color-primary w-25px h-25px bg-body shadow"
																			data-kt-image-input-action="remove" data-bs-toggle="tooltip" data-bs-dismiss="click" title="Remover Logo">
																				<i class="ki-outline ki-cross fs-3"></i>
																			</span>
																			<!--end::Remove button-->
																		</div>
																	</div>
																	<br><br>

																	<span style="font-weight:500">Se preferir criar sua assinatura manualmente, indicamos este site <a href="https://signaturely.com/online-signature/draw/" target="_blank">Signaturely</a></span>
																</div>
																
															</div>
															
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<button id="btn-salvar" type="submit" class="btn btn-primary  btn-salvar">
																		<span class="indicator-label">
																			Salvar
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


						<div class="modal fade" tabindex="-1" id="modal_modelo">
							<div class="modal-dialog">
								<div class="modal-content">
									<div class="modal-header">
										<h3 class="modal-title">Criar Empresa</h3>
										<!--begin::Close-->
										<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
											<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
										</div>
										<!--end::Close-->
									</div>

									<div class="modal-body">
										<p>Insira o nome da empresa que deseja criar.</p>
										<div class="col-md-12">
											<label for="text" class="form-label">Empresa</label>
											<input type="text" class="form-control" id="empnova" placeholder="Insira o nome da empresa">
										</div>
									</div>

									<div class="modal-footer">
										<button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
										<button type="button" class="btn btn-primary" id="btnSalvarEmp">Salvar</button>
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
		<script src="<?php echo  $_SESSION['URL']?>/assets/plugins/custom/fslightbox/fslightbox.bundle.js"></script>


	</body>
	<!--end::Body-->
</html>

<script>

$(document).ready(function() {
	
});

var quill = new Quill('#kt_docs_quill_basic', {
    modules: {
        toolbar: [
           
            ['bold', 'italic', 'underline'],
			['link'],
			[{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
			[{ 'font': [] }],
			[{ 'align': [] }]
        ]
    },
    placeholder: 'Entre com seu texto...',
    theme: 'snow' // or 'bubble'
});

var quill2 = new Quill('#kt_docs_quill_basic2', {
    modules: {
        toolbar: [
           
			['bold', 'italic', 'underline'],
			['link'],
			[{ 'color': [] }, { 'background': [] }],          // dropdown with defaults from theme
			[{ 'font': [] }],
			[{ 'align': [] }]
        ]
    },
    placeholder: 'Entre com seu texto...',
    theme: 'snow' // or 'bubble'
});

const maxFileSize = 8 * 1024 * 1024;
var filepass = 1;

function validateFileSize(inputId, maxSizeInBytes) {
      const input = document.getElementById(inputId);
      const files = input.files;

	if (files.length > 0) {
        const fileSize = files[0].size;
        if (fileSize > maxSizeInBytes) {
			Swal.fire({
				text: "O tamanho do arquivo excede o máximo de 8 MegaBytes ",
				icon: "error",
				buttonsStyling: false,
				confirmButtonText: "Ok",
				customClass: {
					confirmButton: "btn btn-primary"
				}
			});
          input.value = ''; // Limpa o campo de seleção de arquivo
		  filepass = 0;
        }else{
			filepass = 1;
		}
	}
}

document.getElementById('formFile').addEventListener('change', function() {
	validateFileSize('formFile', maxFileSize);
});

document.getElementById('assinaturaFile').addEventListener('change', function() {
	validateFileSize('assinaturaFile', maxFileSize);
});

// Submit button handler
const submitButton = document.getElementById("btn-salvar");
submitButton.addEventListener("click", function (e) {
    // Prevent default button action
    e.preventDefault();
	// Show loading indication
	submitButton.setAttribute("data-kt-indicator", "on");

	// Disable button to avoid multiple click
	submitButton.disabled = true;

	var quillContent = quill.root.innerHTML;
	var quillContent2 = quill2.root.innerHTML;

	if(filepass == true){

		const formData = new FormData();
		formData.append('action', 'config_laudo');
		formData.append('nome', $('#nome').val());
		formData.append('topo',  quillContent);
		formData.append('bottom',  quillContent2);

		var ins = document.getElementById('formFile').files.length;
        for (var x = 0; x < ins; x++) {
            formData.append("file", document.getElementById('formFile').files[x]);
        }
		var ins2 = document.getElementById('assinaturaFile').files.length;
        for (var x = 0; x < ins2; x++) {
            formData.append("file2", document.getElementById('assinaturaFile').files[x]);
        }

		$.post({
			url: "handle.php", // the resource where youre request will go throw
			type: "POST", // HTTP verb
            data: formData,
            contentType: false,
            processData: false,
			success: function (response) {
				if(response == '1'){
					submitButton.removeAttribute("data-kt-indicator");
					// Enable button
					submitButton.disabled = false;
					Swal.fire({
						text: "Dados salvos com sucesso",
						icon: "success",
						buttonsStyling: false,
						confirmButtonText: "Ok",
						customClass: {
							confirmButton: "btn btn-primary"
						}
					});
				}else{
					toastr["error"]("Ocorreu um erro. "+response);
				}
			}
		});
	}
});


 

</script>

<style>
    .image-input-placeholder {
        background-image: url('svg/avatars/blank.svg');
    }

    [data-bs-theme="dark"] .image-input-placeholder {
        background-image: url('svg/avatars/blank-dark.svg');
    }
</style>
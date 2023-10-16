<?php 
require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
?>
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
										<div class="col-xxl-6">
											<!--begin::Engage widget 10-->
											<div class="card card-flush h-md-100">
												<!--begin::Body-->
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
													<!--begin::Wrapper-->
													<div class="mb-10">
													<!-------------------------------------------- -inicio ----------------------------------------->

														<div class="alert alert-dismissible bg-light-primary border border-primary d-flex flex-column flex-sm-row w-100 p-5 mb-10">
															<!--begin::Icon-->
															<i class="ki-duotone ki-file-down  fs-2hx text-primary me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span></i>

															<!--begin::Content-->
															<div class="d-flex flex-column pe-0 pe-sm-10">
															<h5 class="mb-1">Baixar relatório com os valores (garantia e resultado) das amostras.</h5>
																<span>Relatório com os valores <strong>resumido.</strong></span>
															</div>
															<!--begin::Close-->
															<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn ms-sm-auto btn-primary relatorio" 
																data-bs-toggle="modal" data-bs-target="#kt_modal_relatorio">
																Gerar Relatório
															</button>
															<!--end::Close-->
														</div>

													</div>
													<!--begin::Wrapper-->
													<!--begin::Illustration-->
													<!-- <img class="mx-auto h-lg-300px" src="assets/media/illustrations/misc/Multitasking-bro.svg" alt="" /> -->
													
													<!--end::Illustration-->
												</div>
												<!--end::Body-->
											</div>
											<!--end::Engage widget 10-->
										</div>
										<div class="col-xxl-6">
											<!--begin::Engage widget 10-->
											<div class="card card-flush h-md-100">
												<!--begin::Body-->
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
													<!--begin::Wrapper-->
													<div class="mb-10">
													<!-------------------------------------------- -inicio ----------------------------------------->

														<div class="alert alert-dismissible bg-light-info border border-info d-flex flex-column flex-sm-row w-100 p-5 mb-10">
															<!--begin::Icon-->
															<i class="ki-duotone ki-file-down  fs-2hx text-info me-4 mb-5 mb-sm-0"><span class="path1"></span><span class="path2"></span></i>   
															<!--begin::Content-->
															<div class="d-flex flex-column pe-0 pe-sm-10">
															<h5 class="mb-1">Baixar relatório com os valores (garantia, resultado, estudo de causa) das amostras.</h5>
																<span>Relatório com os valores <strong>completo.</strong></span>
															</div>
															<!--begin::Close-->
															<button type="button" class="position-absolute position-sm-relative m-2 m-sm-0 top-0 end-0 btn ms-sm-auto btn-info relatorio_completo" 
																data-bs-toggle="modal" data-bs-target="#kt_modal_relatorio_completo">
																Gerar Relatório
															</button>
															<!--end::Close-->
														</div>

													</div>
													<!--begin::Wrapper-->
													<!--begin::Illustration-->
													<!-- <img class="mx-auto h-lg-300px" src="assets/media/illustrations/misc/Multitasking-bro.svg" alt="" /> -->
													
													<!--end::Illustration-->
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

									<!-----------------------start :: MODAL ------------------>
									<div class="modal fade" tabindex="-1" id="kt_modal_relatorio">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h3 class="modal-title">Relatório</h3>

													<!--begin::Close-->
													<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
														<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
													</div>
													<!--end::Close-->
												</div>

												<div class="modal-body">
													<input type="text" data-kt-docs-table-filter="date" class="form-control form-control-solid ps-10 flatpicker data" id="data" placeholder="Selecione o intervalo de datas" value=""/>  
												</div>

												<div class="modal-footer">
													<button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
													<button type="button" class="btn btn-primary gerar-relatorio">Gerar XLS</button>
												</div>
											</div>
										</div>
									</div>
									<!-----------------------end :: MODAL ------------------>

									<!-----------------------start :: MODAL ------------------>
									<div class="modal fade" tabindex="-1" id="kt_modal_relatorio_completo">
										<div class="modal-dialog">
											<div class="modal-content">
												<div class="modal-header">
													<h3 class="modal-title">Relatório</h3>

													<!--begin::Close-->
													<div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
														<i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
													</div>
													<!--end::Close-->
												</div>

												<div class="modal-body">
													<input type="text" data-kt-docs-table-filter="date" class="form-control form-control-solid ps-10 flatpicker data" id="datacompleta" placeholder="Selecione o intervalo de datas" value=""/>  
												</div>

												<div class="modal-footer">
													<button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
													<button type="button" class="btn btn-primary gerar-relatorio-completo">Gerar XLS</button>
												</div>
											</div>
										</div>
									</div>
									<!-----------------------end :: MODAL ------------------>

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
	</body>
	<!--end::Body-->
</html>

<script src="<?php echo  $_SESSION['URL']?>/assets/js/flatpicker-pt.js"></script>

<script>

$(".flatpicker").flatpickr({
    dateFormat: "d/m/Y",
    mode: "range",
    locale: "pt",
    rangeSeparator: ' até ',
    
});

$('body').on('click', '.gerar-relatorio', function(e) {
	$("#kt_modal_relatorio").modal('hide');
	Swal.fire({
		icon: 'info',
		title: 'Carregando...',
		text: 'Gerando relatório!',
		timer: 2000,
  		timerProgressBar: true,
		showConfirmButton: false
	})
    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'gerar_relatorio', data: $('#data').val()},
        success: function (response) {
			if(response != '0' && response != '2'){

				var downloadLink = response;
				console.log(downloadLink);
				// Crie um link para iniciar o download
				var a = document.createElement('a');
				a.href = downloadLink;
				a.download = downloadLink; // Defina o nome do arquivo
				a.style.display = 'none';

				// Anexe o link ao corpo do documento
				document.body.appendChild(a);

				// Simule um clique no link para iniciar o download
				a.click();

				// Remova o link após o download
				document.body.removeChild(a);

				// Você também pode excluir o arquivo temporário no servidor após o download
				setTimeout(function() {
					$.post({
						url: "handle.php", // Script PHP para excluir o arquivo temporário
						data: { action: 'apagar_relatorio', arquivo: downloadLink },
						success: function (result) {
						}
					});
				}, 5000);
			}else{
				Swal.fire({
				icon: 'error',
				title: 'Oops...',
				text: 'Nenhum registro encontrado.',
				})
			}
        }
    });
});


$('body').on('click', '.gerar-relatorio-completo', function(e) {
$("#kt_modal_relatorio_completo").modal('hide');
Swal.fire({
	icon: 'info',
	title: 'Carregando...',
	text: 'Gerando relatório!',
	timer: 2000,
	  timerProgressBar: true,
	showConfirmButton: false
})
$.post({
	url: "handle.php", // the resource where youre request will go throw
	type: "POST", // HTTP verb
	data: {action: 'gerar_relatorio_completo', data: $('#datacompleta').val()},
	success: function (response) {
		if(response != '0' && response != '2'){

			var downloadLink = response;
			console.log(downloadLink);
			// Crie um link para iniciar o download
			var a = document.createElement('a');
			a.href = downloadLink;
			a.download = downloadLink; // Defina o nome do arquivo
			a.style.display = 'none';
			// Anexe o link ao corpo do documento
			document.body.appendChild(a);
			// Simule um clique no link para iniciar o download
			a.click();
			// Remova o link após o download
			document.body.removeChild(a);
			// Você também pode excluir o arquivo temporário no servidor após o download
			setTimeout(function() {
				$.post({
					url: "handle.php", // Script PHP para excluir o arquivo temporário
					data: { action: 'apagar_relatorio', arquivo: downloadLink },
					success: function (result) {
					}
				});
			}, 5000);
		}else{
			Swal.fire({
			icon: 'error',
			title: 'Oops...',
			text: 'Nenhum registro encontrado.',
			})
		}
	   
	}
});
});

</script>
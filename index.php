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
										<div class="col-xxl-12">
											<!--begin::Engage widget 10-->
											<div class="card card-flush h-md-100">
												<!--begin::Body-->
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0" style="background-position: 100% 50%; background-image:url('assets/media/stock/900x600/42.png')">
													<!--begin::Wrapper-->
													<div class="mb-10">
													<!-------------------------------------------- -inicio ----------------------------------------->
														<h5>Novidades</h5>
														<!--begin::Action-->
														<div class="row row-cols-1 row-cols-lg-12 g-9 py-3">
															<div class="col"><div class="card card-bordered">
																<div class="ribbon ribbon-end"><div class="ribbon-label bg-success">Concluído</div></div>
																<div class="" style="padding:5px">Identificação Carta na exportação para PDF.<br><span style="font-weight:500">Para Todos</span></div>
															</div></div>																
														</div>
														<!--begin::Row-->
														<div class="row row-cols-1 row-cols-lg-12 g-9 py-3">
															<div class="col"><div class="card card-bordered">
																<div class="ribbon ribbon-end"><div class="ribbon-label bg-success">Em Teste</div></div>
																<div class="" style="padding:5px">Consulta de dados via API REST. <br><span style="font-weight:500">Para Todos</span></div>
															</div></div>																
														</div>
														<!--end::Row-->
														<div class="row row-cols-1 row-cols-lg-12 g-9 py-3">
															<!--begin::Col-->
															<div class="col"><div class="card card-bordered">
																	<div class="ribbon ribbon-end"><div class="ribbon-label bg-primary">Em Breve</div></div>
																	<div class="" style="padding:5px">Exibir todos os produtos de uma carta para selecionar qual deseja baixar no laudo. <br><span style="font-weight:500">Para Todos</span>
																</div></div>
															</div>
														</div>
														<div class="row row-cols-1 row-cols-lg-12 g-9 py-3">
															<!--begin::Col-->
															<div class="col"><div class="card card-bordered">
																	<div class="ribbon ribbon-end"><div class="ribbon-label bg-primary">Em Breve</div></div>
																	<div class="" style="padding:5px">Histórico de cada ação no sistema. Estamos salvando cada ação realizada, mas ainda não há visão. <br><span style="font-weight:500">Para Todos</span>
																	</div></div>
															</div>
														</div>
														<div class="row row-cols-1 row-cols-lg-12 g-9 py-3">
															<!--begin::Col-->
															<div class="col"><div class="card card-bordered">
																	<div class="ribbon ribbon-end"><div class="ribbon-label bg-primary">Em Breve</div></div>
																	<div class="" style="padding:5px">Gerenciamento financeiro (assinatura do sistema, acesso a nota fiscal). <br><span style="font-weight:500">Para Contratante</span>
																	</div></div>
															</div>
														</div>

														<!--begin::Accordion-->
														<div class="accordion accordion-icon-collapse" id="kt_accordion_3">
															<!--begin::Item-->
															<div class="mb-5">
																<!--begin::Header-->
																<div class="accordion-header py-3 d-flex" data-bs-toggle="collapse" data-bs-target="#kt_accordion_3_item_1">
																	<span class="accordion-icon">
																		<i class="ki-duotone ki-plus-square fs-3 accordion-icon-off"><span class="path1"></span><span class="path2"></span><span class="path3"></span></i>
																		<i class="ki-duotone ki-minus-square fs-3 accordion-icon-on"><span class="path1"></span><span class="path2"></span></i>
																	</span>
																	<h3 class="fs-4 fw-semibold mb-0 ms-4">Vídeo Tutorial</h3>
																</div>
																<!--end::Header-->

																<!--begin::Body-->
																<div id="kt_accordion_3_item_1" class="fs-6 collapse show ps-10" data-bs-parent="#kt_accordion_3">
																<div class="row">
																	<?php  echo (($_SESSION['USUTIPO'] == 'QUALIDADE') ? '<div class="col-lg-6 d-flex"><iframe width="560" height="315" src="https://www.youtube.com/embed/y0h-vCOY5S4" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>' : ''); ?>
																		<div class="col-lg-6 d-flex"><iframe width="560" height="315" src="https://www.youtube.com/embed/vpfSKhlg_mY" title="YouTube video player" frameborder="0" allow="accelerometer; autoplay; clipboard-write; encrypted-media; gyroscope; picture-in-picture; web-share" allowfullscreen></iframe></div>
																	</div>
																</div>
																<!--end::Body-->
															</div>
															<!--end::Item-->
														</div>
														<!--end::Accordion-->

														<!--begin::Action-->
													</div>
													<!--begin::Wrapper-->
													<!--begin::Illustration-->
													<img class="mx-auto h-lg-300px" src="assets/media/illustrations/misc/Multitasking-bro.svg" alt="" />
													
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
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
		<!--begin::Fonts(mandatory for all pages)-->
            <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Inter:300,400,500,600,700" />
            <!--end::Fonts-->
            <!--begin::Global Stylesheets Bundle(mandatory for all pages)-->
            <link href="../assets/plugins/global/plugins.bundle.css" rel="stylesheet" type="text/css" />
            <link href="../assets/css/style.bundle.css" rel="stylesheet" type="text/css" />
            <link href="../assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css" />
	</head>
	<!--end::Head-->

	<body id="kt_app_body" data-kt-app-sidebar-minimize="off" data-kt-app-layout="dark-sidebar" data-kt-app-header-fixed="true" data-kt-app-sidebar-enabled="true" data-kt-app-sidebar-fixed="true" data-kt-app-sidebar-hoverable="true" data-kt-app-sidebar-push-header="true" data-kt-app-sidebar-push-toolbar="true" data-kt-app-sidebar-push-footer="true" data-kt-app-toolbar-enabled="true" class="app-default">
		<!--begin::Theme mode setup on page load-->
		<script>var defaultThemeMode = "light"; var themeMode; if ( document.documentElement ) { if ( document.documentElement.hasAttribute("data-bs-theme-mode")) { themeMode = document.documentElement.getAttribute("data-bs-theme-mode"); } else { if ( localStorage.getItem("data-bs-theme") !== null ) { themeMode = localStorage.getItem("data-bs-theme"); } else { themeMode = defaultThemeMode; } } if (themeMode === "system") { themeMode = window.matchMedia("(prefers-color-scheme: light)").matches ? "light" : "light"; } document.documentElement.setAttribute("data-bs-theme", themeMode); }</script>
		<!--end::Theme mode setup on page load-->
		<!--begin::App-->
		<div class="d-flex flex-column flex-root app-root" id="kt_app_root">
			<!--begin::Page-->
			<div class="app-page flex-column flex-column-fluid" id="kt_app_page">
				<!--begin::Header-->
				<!--=------------------------------------------------- START TOPBAR------------------------------------------------------------------------->
					
				<!--=------------------------------------------------- END TOPBAR------------------------------------------------------------------------->
				<!--end::Header-->
				<!--begin::Wrapper-->
				<div class="app-wrapper flex-column flex-row-fluid" id="kt_app_wrapper">
					<!--=------------------------------------------------- START MENU LATERAL------------------------------------------------------------------------->
					<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
                        <!--begin::Logo-->
                        <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
                            <!--begin::Logo image-->
                            <a href="#">
                                <img alt="Logo" src="../img/logo.png" class="h-25px app-sidebar-logo-default" />
                                <img alt="Logo" src="../img/fav/favicon-32x32.png" class="h-25px app-sidebar-logo-minimize" />
                            </a>
                        
                            <div id="kt_app_sidebar_toggle" class="app-sidebar-toggle btn btn-icon btn-shadow btn-sm btn-color-muted btn-active-color-primary body-bg h-30px w-30px position-absolute top-50 start-100 translate-middle rotate" data-kt-toggle="true" data-kt-toggle-state="active" data-kt-toggle-target="body" data-kt-toggle-name="app-sidebar-minimize">
                                <i class="ki-duotone ki-double-left fs-2 rotate-180">
                                    <span class="path1"></span>
                                    <span class="path2"></span>
                                </i>
                            </div>
                            <!--end::Sidebar toggle-->
                        </div>
                        <!--end::Logo-->
                        <!--begin::sidebar menu-->
                        <div class="app-sidebar-menu overflow-hidden flex-column-fluid">
                            <!--begin::Menu wrapper-->
                            <div id="kt_app_sidebar_menu_wrapper" class="app-sidebar-wrapper hover-scroll-overlay-y my-5" data-kt-scroll="true" data-kt-scroll-activate="true" data-kt-scroll-height="auto" data-kt-scroll-dependencies="#kt_app_sidebar_logo, #kt_app_sidebar_footer" data-kt-scroll-wrappers="#kt_app_sidebar_menu" data-kt-scroll-offset="5px" data-kt-scroll-save-state="true">
                                <!--begin::Menu-->
                                <div class="menu menu-column menu-rounded menu-sub-indention px-3" id="#kt_app_sidebar_menu" data-kt-menu="true" data-kt-menu-expand="false">
                                    
                                    <!--begin:Menu item-->
                                    <div class="menu-item">
                                        <!--begin:Menu content-->
                                        <div class="menu-content">
                                            <span class="menu-heading fw-bold text-uppercase fs-7">NAVEGAÇÃO</span>
                                        </div>
                                        <!--end:Menu content-->
                                    </div>

                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link list-group-item list-group-item-action active" href="#list-item-1">
                                            <span class="menu-icon">
                                                <i class="fa-solid fa-book fa-fw"></i>
                                            </span>
                                            <span class="menu-title">Análises</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link list-group-item list-group-item-action" href="#list-item-2">
                                            <span class="menu-icon">
                                                <i class="fa-solid fa-book fa-fw"></i>
                                            </span>
                                            <span class="menu-title">Análises</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    <div class="menu-item">
                                        <!--begin:Menu link-->
                                        <a class="menu-link list-group-item list-group-item-action" href="#list-item-3">
                                            <span class="menu-icon">
                                                <i class="fa-solid fa-book fa-fw"></i>
                                            </span>
                                            <span class="menu-title">Análises</span>
                                        </a>
                                        <!--end:Menu link-->
                                    </div>
                                    
                                
                                </div>
                                <!--end::Menu-->
                            </div>
                            <!--end::Menu wrapper-->
                        </div>
                        <!--end::sidebar menu-->
                        
                    </div>
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
													<div class="container d-flex flex-column flex-lg-row">
													<!-------------------------------------------- -inicio ----------------------------------------->
														<div class="row row-cols-1 row-cols-lg-12 g-9 py-3">

                                                            <div class="bd-example">
                                                                <div class="row">
                                                                    <div class="col-6">
                                                                        <div data-bs-spy="scroll" data-bs-target="#list-example" data-bs-offset="0" class="scrollspy-example" tabindex="0">
                                                                            <h4 id="list-item-1">Bem-vindo à Documentação da API.</h4>
                                                                            <p>
                                                                            Seja bem-vindo à documentação abrangente da API de conexão da Diges. Esta API foi projetada para proporcionar uma maneira eficiente e flexível de
                                                                            interagir com os dados das amostras e o solicitante da requisição. Se você é um desenvolvedor de aplicativos, um engenheiro
                                                                            de software ou qualquer pessoa interessada em utilizar nossos serviços, esta documentação irá guiá-lo através dos recursos, endpoints e práticas
                                                                            recomendadas para uma integração bem-sucedida.
                                                                            </p>
                                                                            <h4 id="list-item-2">Visão Geral da API</h4>
                                                                            <p>
                                                                            A API foi criada com foco na simplicidade, usabilidade e escalabilidade. Ela permite que você consulte dados que foram manipulador dentro do sistema. 
                                                                            Utilizando os princípios da arquitetura REST (Representational State Transfer),
                                                                            nossa API segue as melhores práticas da indústria para oferecer uma experiência de desenvolvimento consistente e confiável.
                                                                            </p>
                                                                            
                                                                            <h4 id="list-item-3">Autenticação:</h4>
                                                                            <p>
                                                                            Antes de começar a utilizar a API, é necessário realizar a autenticação. Dentro do sistema na área do usuário (canto superior direito) existe a opção "Token de Autenticação". 
                                                                            A Criação de um Token é obrigatória para realizar uma requisição, lembrando que o token irá expirar com o prazo de 90 dias. Se você possuir uma aplicação com o token já aplicado, 
                                                                            e clicar na opção de "Gerar Token" dentro do painel, terá de alterar também na aplicação. <span class="badge badge-light-primary">EM BREVE</span> 
                                                                            será possível atualizar o Token a partir do usuário e senha em uma nova api.
                                                                            </p>
                                                                            <h4 id="list-item-4">Formato dos Dados:</h4>
                                                                            <p>
                                                                            A API [Nome da API] utiliza o formato JSON (JavaScript Object Notation) para troca de dados. Isso permite uma estrutura clara e legível para representar
                                                                            informações em suas solicitações e respostas.
                                                                            </p>
                                                                            <h4 id="list-item-5">Como Usar Esta Documentação:</h4>
                                                                            <p>
                                                                            Esta documentação foi organizada de forma a facilitar a sua navegação e compreensão. Você encontrará informações detalhadas sobre cada endpoint,
                                                                            exemplos de solicitações e respostas, além de dicas e práticas recomendadas para otimizar sua integração.
                                                                            </p>
                                                                            <h4 id="list-item-6">Vamos Começar:</h4>
                                                                            <p>
                                                                            Se você está pronto para começar a explorar a API [Nome da API], recomendamos começar pela seção [link para a seção de início rápido ou primeiros passos].
                                                                            Lá, você encontrará um guia passo a passo para realizar sua primeira chamada à API.
                                                                            </p>
                                                                            <h4 id="list-item-7">Suporte:</h4>
                                                                            <p>
                                                                            Se você tiver alguma dúvida, problema ou precisar de assistência durante a integração, nossa equipe de suporte está pronta para ajudar. Entre em
                                                                            contato conosco através de [informações de contato para suporte].
                                                                            </p>
                                                                            <p>
                                                                            Agradecemos por escolher a API [Nome da API]. Estamos empolgados para ver as soluções inovadoras que você irá criar utilizando nossos recursos.
                                                                            Vamos começar a explorar juntos!
                                                                            </p>
                                                                            <p>
                                                                            Espero que este exemplo lhe dê uma ideia de como começar a introdução da documentação da sua API REST. Certifique-se de personalizá-la de acordo
                                                                            com os detalhes específicos da sua API e das necessidades dos seus usuários.
                                                                            </p>

                                                                            </div>
                                                                        </div>
                                                                    </div>
                                                                   
                                                                </div>
                                                            </div>
														</div>

													</div>
													<!--begin::Wrapper-->
													<!--begin::Illustration-->
													<img class="mx-auto h-lg-300px" src="../assets/media/illustrations/misc/Multitasking-bro.svg" alt="" />
													
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
						<div id="kt_app_footer" class="app-footer">
                            <!--begin::Footer container-->
                            <div class="app-container container-fluid d-flex flex-column flex-md-row flex-center flex-md-stack py-3">
                                <!--begin::Copyright-->
                                <div class="text-dark order-2 order-md-1">
                                    <span class="text-muted fw-semibold me-1">2023&copy;</span>
                                    <a href="https://www.diges.com.br" target="_blank" class="text-gray-800 text-hover-primary">Diges</a>
                                </div>
                                <!--end::Copyright-->
                                <!--begin::Menu-->
                                <!-- <ul class="menu menu-gray-600 menu-hover-primary fw-semibold order-1">
                                    <li class="menu-item">
                                        <a href="https://keenthemes.com" target="_blank" class="menu-link px-2">About</a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="https://devs.keenthemes.com" target="_blank" class="menu-link px-2">Support</a>
                                    </li>
                                    <li class="menu-item">
                                        <a href="https://1.envato.market/EA4JP" target="_blank" class="menu-link px-2">Purchase</a>
                                    </li>
                                </ul> -->
                                <!--end::Menu-->
                            </div>
                            <!--end::Footer container-->
                        </div>
						<!--end::Footer-->
					</div>
					<!--end:::Main-->
				</div>
				<!--end::Wrapper-->
			</div>
			<!--end::Page-->
		</div>
		<!--end::Drawers-->
        <!-------------------------------- FOOTER:: ====================================-->
		<!--begin::Scrolltop-->
        <div id="kt_scrolltop" class="scrolltop" data-kt-scrolltop="true">
            <i class="ki-duotone ki-arrow-up">
                <span class="path1"></span>
                <span class="path2"></span>
            </i>
        </div>
        <!--end::Scrolltop-->

        <!--end::Modals-->
        <!--begin::Javascript-->
        <script>var hostUrl = "assets/";</script>
        <!--begin::Global Javascript Bundle(mandatory for all pages)-->
        <script src="../assets/plugins/global/plugins.bundle.js"></script>
        <script src="../assets/js/scripts.bundle.js"></script>

	</body>
	<!--end::Body-->
</html>
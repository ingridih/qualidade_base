<?php 
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';


?>

<!--begin::Sidebar-->
<div id="kt_app_sidebar" class="app-sidebar flex-column" data-kt-drawer="true" data-kt-drawer-name="app-sidebar" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_app_sidebar_mobile_toggle">
    <!--begin::Logo-->
    <div class="app-sidebar-logo px-6" id="kt_app_sidebar_logo">
        <!--begin::Logo image-->
        <a href="<?php echo $_SESSION['URL']?>">
            <img alt="Logo" src="<?php echo (isset($_SESSION['URL']) ? $_SESSION['URL']  : '' ); ?>/img/logo.png" class="h-25px app-sidebar-logo-default" />
            <img alt="Logo" src="<?php echo (isset($_SESSION['URL']) ? $_SESSION['URL']  : '' ); ?>/img/fav/favicon-32x32.png" class="h-25px app-sidebar-logo-minimize" />
        </a>
        <!--end::Logo image-->
        <!--begin::Sidebar toggle-->
        <!--begin::Minimized sidebar setup: 
            if (isset($_COOKIE["sidebar_minimize_state"]) && $_COOKIE["sidebar_minimize_state"] === "on") {
                1. "src/js/layout/sidebar.js" adds "sidebar_minimize_state" cookie value to save the sidebar minimize state.
                2. Set data-kt-app-sidebar-minimize="on" attribute for body tag.
                3. Set data-kt-toggle-state="active" attribute to the toggle element with "kt_app_sidebar_toggle" id.
                4. Add "active" class to to sidebar toggle element with "kt_app_sidebar_toggle" id.
            }-->
        
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
                <!--end:Menu item-->
                <div class="menu-item">
                    <!--begin:Menu link-->
                    <a class="menu-link" href="<?php echo $_SESSION['URL'].'/index'; ?>">
                        <span class="menu-icon">
                            <i class="fa-solid fa-house fa-fw"></i>
                        </span>
                        <span class="menu-title">Início</span>
                    </a>
                    <!--end:Menu link-->
                </div>
                <?php 
                    $MENU = null;
                    if($_SESSION['USUTIPO'] == 'LABORATORIO'){
                        $MENU = '
                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link" href="'.$_SESSION['URL'].'/module/quality/lab/analysis">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-book fa-fw"></i>
                                </span>
                                <span class="menu-title">Análises</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->
                        
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <!--begin:Menu link-->
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fa fa-cog"></i>
                                </span>
                                <span class="menu-title">Configurações</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <!--end:Menu link-->
                            <!--begin:Menu sub-->
                            <div class="menu-sub menu-sub-accordion" kt-hidden-height="100" style="display: none; overflow: hidden;">
                                <!--begin:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link" href="'.$_SESSION['URL'].'/module/quality/lab/laudo">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Laudo</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                                
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link" href="'.$_SESSION['URL'].'/module/methods">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Metodos de Análise</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link" href="'.$_SESSION['URL'].'/module/user/userlab">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Usuários Cadastrados</span>
                                    </a>
                                    <!--end:Menu link-->
                                </div>
                                <!--end:Menu item-->
                        
                            </div>
                            <!--end:Menu sub-->
                        </div>';
                        
                    }if($_SESSION['USUTIPO'] == 'QUALIDADE'){
                        $MENU = '
                        <div data-kt-menu-trigger="click" class="menu-item menu-accordion">
                            <span class="menu-link">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-book fa-fw"></i>
                                </span>
                                <span class="menu-title">Análises</span>
                                <span class="menu-arrow"></span>
                            </span>
                            <div class="menu-sub menu-sub-accordion" kt-hidden-height="125" style="display: none; overflow: hidden;">
                                <div class="menu-item">
                                    <!--begin:Menu link-->
                                    <a class="menu-link" href="'.$_SESSION['URL'].'/module/quality/quali/analysis"> 
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Produtos</span>
                                    </a>
                                </div>
                                <div class="menu-item">
                                    <a class="menu-link" href="'.$_SESSION['URL'].'/module/granu">
                                        <span class="menu-bullet">
                                            <span class="bullet bullet-dot"></span>
                                        </span>
                                        <span class="menu-title">Granulometria</span>
                                    </a>
                                </div>
                            </div>
                        </div>

                        <!--begin:Menu item-->
                        <div class="menu-item">
                            <!--begin:Menu link-->
                            <a class="menu-link" href="'.$_SESSION['URL'].'/module/element">
                                <span class="menu-icon">
                                    <i class="fa-solid fa-vial fa-fw"></i>
                                </span>
                                <span class="menu-title">Elementos</span>
                            </a>
                            <!--end:Menu link-->
                        </div>
                        <!--end:Menu item-->';

                        IF($_SESSION['USUADMIN'] == '1'){ 
                            $MENU .= '<!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link" href="'.$_SESSION['URL'].'/module/user/user-list">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-user-gear fa-fw"></i>
                                    </span>
                                    <span class="menu-title">Usuários</span>
                                </a>
                                <!--end:Menu link-->
                            </div>';
                            $MENU .= '<!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link" href="'.$_SESSION['URL'].'/module/lab/lab-list">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-flask fa-fw"></i>
                                    </span>
                                    <span class="menu-title">Laboratorios</span>
                                </a>
                                <!--end:Menu link-->
                            </div>';
                            $MENU .= '
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link" href="'.$_SESSION['URL'].'/module/report" >
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-file-export fa-fw"></i>
                                    </span>
                                    <span class="menu-title">Relatórios e Indicadores</span>
                                </a>
                                <!--end:Menu link-->
                            </div>';
                            $MENU .= '
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link" href="https://www.diges.com.br/login">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-hand-holding-dollar fa-fw fs-5"></i>
                                    </span>
                                    <span class="menu-title">Financeiro</span>
                                </a>
                                <!--end:Menu link-->
                            </div>';
                            $MENU .= '
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link" href="'.$_SESSION['URL'].'/module/support/">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-info fa-fw fs-3" style="color:#dbb73a"></i>
                                    </span>
                                    <span class="menu-title">Ticket (Suporte Técnico e Solicitações)</span>
                                </a>
                                <!--end:Menu link-->
                            </div>';
                        }
                    }
                    $MENU .= '
                            <!--end:Menu item--><!--begin:Menu item-->
                            <div class="menu-item">
                                <!--begin:Menu link-->
                                <a class="menu-link" href="https://www.diges.com.br/api/documentation"  target="_blank">
                                    <span class="menu-icon">
                                        <i class="fa-solid fa-laptop-code fa-fw"></i>
                                    </span>
                                    <span class="menu-title">Documentação API Rest</span>
                                </a>
                                <!--end:Menu link-->
                            </div>';
                   
                    ECHO $MENU;
                ?>
            </div>
            <!--end::Menu-->
        </div>
        <!--end::Menu wrapper-->
    </div>
    <!--end::sidebar menu-->
    
</div>
<!--end::Sidebar-->

<script>
    document.getElementById('kt_app_sidebar_toggle').addEventListener('click', function() {
        // Define o valor do cookie com base no estado atual da barra lateral
        var sidebarState = document.body.getAttribute('data-kt-app-sidebar-minimize') === 'on' ? 'off' : 'on';
        document.cookie = 'sidebar_minimize_state=' + sidebarState;
    });
</script>
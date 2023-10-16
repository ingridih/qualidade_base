<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL_ticket::getConnection();
    $Conexao2 = ConexaoMYSQL::getConnection();

    $array_ticket = array();
    $status = array(
        'A' => 'Aguardando Resposta',
        'Acolor' => 'primary',
        'R' => 'Respondido',
        'Rcolor' => 'success',
        'F' => 'Finalizado',
        'Fcolor' => 'secondary',
        'C' => 'Cancelado',
        'Ccolor' => 'danger',
    );

    $active = 1;
    $where = "T_RESPOSTA IS NULL";
   
    if(isset($_GET['sts'])){
        if($_GET['sts'] == 'R'){
            $active = 2;
            $where .= " AND T_STATUS = 'R' ";
        }else if($_GET['sts'] == 'F'){
            $active = 3;
            $where .= " AND T_STATUS = 'F' ";
        }else if($_GET['sts'] == 'S'){
            $active = 4;
            $where .= " AND T_FAVORITO = '1' ";
        }
    }else{
        $active = 1;
    }

    $aguardando = 0;
    $todos = 0;
    $respondido = 0;
    $finalizado = 0;
    $favoritos = 0;

    $query = $Conexao->query("SELECT T_ID, T_RESPOSTA, T_TIPO, T_STATUS, T_FAVORITO 
        FROM TICKET 
        WHERE T_USUARIO = '".$_SESSION['USUID']."' AND T_RESPOSTA IS NULL AND T_URL = '".$_SESSION['URL']."' AND T_EMAIL_SOLICITANTE = '".$_SESSION['USUEMAIL']."'");
        while ($row = $query->fetch()) {
            $todos++;
            if($row['T_STATUS'] == 'A'){
                $aguardando++;
            }else if($row['T_STATUS'] == 'F'){
                $finalizado++;
            }else if($row['T_STATUS'] == 'R'){
                $respondido++;
            }
            if($row['T_FAVORITO'] == 1){
                $favoritos++;
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
                                <!----------------------------------------------------INSERIR DENTRO DO CONTENT---------------------------------------------------------------------------->
                                    <div class="card mb-5 mb-xl-10">
                                        <div class="card-header border-0 pt-5">
											<h3 class="card-title align-items-start flex-column">
												<span class="card-label fw-bold fs-3 mb-1">Suporte Técnico</span>
											</h3>
										</div>
                                        
                                        <div class="card-body ">
											
                                            <div class="d-flex flex-column flex-lg-row">
                                                <!--begin::Sidebar-->
                                                <div class="d-none d-lg-flex flex-column flex-lg-row-auto w-100 w-lg-275px" data-kt-drawer="true" data-kt-drawer-name="inbox-aside" data-kt-drawer-activate="{default: true, lg: false}" data-kt-drawer-overlay="true" data-kt-drawer-width="225px" data-kt-drawer-direction="start" data-kt-drawer-toggle="#kt_inbox_aside_toggle">
                                                    <!--begin::Sticky aside-->
                                                    <div class="card card-flush mb-0" data-kt-sticky="true" data-kt-sticky-name="inbox-aside-sticky" data-kt-sticky-offset="{default: false, xl: '100px'}" data-kt-sticky-width="{lg: '275px'}" data-kt-sticky-left="auto" data-kt-sticky-top="100px" data-kt-sticky-animation="false" data-kt-sticky-zindex="95">
                                                        <!--begin::Aside content-->
                                                        <div class="card-body">
                                                            <!--begin::Button-->
                                                            <a href="#" data-bs-toggle="modal" data-bs-target="#kt_modal_new_ticket" class="btn btn-primary fw-bold w-100 mb-8">Novo Ticket</a>
                                                            <!--end::Button-->
                                                            <!--begin::Menu-->
                                                            <div class="menu menu-column menu-rounded menu-state-bg menu-state-title-primary menu-state-icon-primary menu-state-bullet-primary mb-10">
                                                                <!--begin::Menu item-->
                                                                <div class="menu-item mb-3">
                                                                    <!--begin::Inbox-->
                                                                    <a href="index" class="menu-link <?php echo ($active == 1) ? 'active' : ''?>">
                                                                        <span class="menu-icon"><i class="fa-solid fa-ticket fa-fw"></i></span>
                                                                        <span class="menu-title fw-bold">Todos</span>
                                                                        <span class="badge badge-light-primary"><?php echo $todos?></span>
                                                                    </a>
                                                                    <!--end::Inbox-->
                                                                </div>
                                                                <!--end::Menu item-->
                                                                <div class="menu-item mb-3">
                                                                    <!--begin::In progress-->
                                                                    <a href="index?sts=R" class="menu-link <?php echo ($active == 2) ? 'active' : ''?>" style="color: black; text-decoration: none;">
                                                                        <span class="menu-icon"><i class="fa-solid fa-reply fa-fw"></i></span>
                                                                        <span class="menu-title fw-semibold">Respondidos</span>
                                                                        <span class="badge badge-light-success"><?php echo $respondido?></span>
                                                                    </a>
                                                                    <!--end::In progress-->
                                                                </div>
                                                                <div class="menu-item mb-3">
                                                                    <!--begin::In progress-->
                                                                    <a href="index?sts=F" class="menu-link <?php echo ($active == 3) ? 'active' : ''?>" style="color: black; text-decoration: none;">
                                                                        <span class="menu-icon"><i class="fa-regular fa-circle-check fa-fw"></i></span>
                                                                        <span class="menu-title fw-semibold">Finalizado</span>
                                                                        <span class="badge badge-light-secondary"><?php echo $finalizado?></span>
                                                                    </a>
                                                                    <!--end::In progress-->
                                                                </div>
                                                                <div class="menu-item mb-3">
                                                                    <!--begin::In progress-->
                                                                    <a href="index?sts=S" class="menu-link <?php echo ($active == 4) ? 'active' : ''?>" style="color: black; text-decoration: none;">
                                                                        <span class="menu-icon"><i class="fa-solid fa-star fa-fw"></i></span>
                                                                        <span class="menu-title fw-semibold">Favoritos</span>
                                                                        <span class="badge badge-light-warning"><?php echo $favoritos?></span>
                                                                    </a>
                                                                    <!--end::In progress-->
                                                                </div>
                                                                <!--end::Menu item-->
                                                            </div>
                                                            <!--end::Menu-->
                                                        </div>
                                                        <!--end::Aside content-->
                                                    </div>
                                                    <!--end::Sticky aside-->
                                                </div>
                                                <!--end::Sidebar-->
                                                <!--begin::Content-->
                                                <div class="flex-lg-row-fluid ms-lg-7 ms-xl-10">
                                                    <!--begin::Card-->
                                                    <div class="card">
                                                        <div class="card-header align-items-center py-5 gap-2 gap-md-5">
                                                            
                                                            <!--begin::Actions-->
                                                            <div class="d-flex align-items-center flex-wrap gap-2">
                                                                <!--begin::Search-->
                                                                <div class="d-flex align-items-center position-relative">
                                                                    <i class="ki-duotone ki-magnifier fs-3 position-absolute ms-4"><span class="path1"></span><span class="path2"></span>
                                                                    </i>
                                                                    <input type="text" data-kt-inbox-listing-filter="search" class="form-control form-control-sm form-control-solid mw-100 min-w-125px min-w-lg-150px min-w-xxl-200px ps-11" placeholder="Buscar" />
                                                                </div>
                                                                <!--end::Search-->
                                                                <a href="#" class="btn btn-sm btn-icon btn-color-primary btn-light btn-active-light-primary d-lg-none" data-bs-toggle="tooltip" data-bs-dismiss="click" data-bs-placement="top" id="kt_inbox_aside_toggle" aria-label="Toggle inbox menu" data-bs-original-title="Toggle inbox menu" data-kt-initialized="1">
                                                                <i class="ki-duotone ki-burger-menu-2 fs-3 m-0"><span class="path1"></span><span class="path2"></span><span class="path3"></span><span class="path4"></span><span class="path5"></span><span class="path6"></span><span class="path7"></span><span class="path8"></span><span class="path9"></span><span class="path10"></span></i>    </a>
                                                            </div>
                                                                
                                                            <!--end::Actions-->
                                                        </div>
                                                        <div class="card-body p-0">
                                                            <div style="text-align:center;margin-top: 10px;" id="div_spin">
                                                                <span class="spinner-border text-primary" role="status"></span>
                                                                <span class="text-muted fs-6 fw-semibold mt-5">Carregando...</span>
                                                            </div>
                                                            <!--begin::Table-->
                                                            <table class="table table-hover table-row-dashed fs-6 gy-5 my-0" id="kt_inbox_listing" >
                                                                <thead class="d-none">
                                                                    <tr>																
                                                                        <th>Actions</th>
                                                                        <th>Author</th>
                                                                        <th>Title</th>
                                                                        <th>Date</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody id="corpinho" style="display:none">
                                                                <?php 
                                                                    $query = $Conexao->query("SELECT T_ID, T_RESPOSTA, T_TIPO, T_URGENCIA, T_ASSUNTO, T_DESCRICAO, T_USUARIO, 
                                                                    DATE_FORMAT(T_DATA, '%d/%m/%Y %H:%i') T_DATA, T_RESPOND_USU, T_STATUS, T_FAVORITO, T_EMAIL, T_SOLICITANTE 
                                                                    FROM TICKET WHERE ". $where);
                                                                    while ($row = $query->fetch()) {
                                                                       echo '<tr>
                                                                            <td style="width:10%">
                                                                                <a href="#" class="btn btn-icon '.(($row['T_FAVORITO'] == 1) ? 'btn-color-warning' : 'btn-color-gray-400').' w-35px h-35px favorito" data-bs-toggle="tooltip" id="'.$row['T_ID'].'" data-bs-placement="right" title="Favorito">
                                                                                    <i class="ki-duotone ki-star fs-3" onclick="toggleColor(this)"></i>
                                                                                </a>
                                                                            </td>
                                                                            <td style="width:20%">
                                                                                <a href="ticket?id='.base64_encode($row['T_ID']).'" class="d-flex align-items-center text-dark">
                                                                                    <span class="fw-semibold">'.$row['T_SOLICITANTE'].'</span>
                                                                                </a>
                                                                            </td>
                                                                            <td  style="">
                                                                                <div class="text-dark gap-1 pt-2">
                                                                                    <a href="ticket?id='.base64_encode($row['T_ID']).'"  class="text-dark">
                                                                                        <span class="fw-bold">'.$row['T_ASSUNTO'].'</span>
                                                                                    </a>
                                                                                </div>
                                                                                <div class="badge badge-'.$status[$row['T_STATUS'].'color'].'">'.$status[$row['T_STATUS']].'</div>
                                                                            </td>
                                                                            <td class="text-end fs-7 pe-9"  style="width:10%">
                                                                                <span class="fw-semibold">'.$row['T_DATA'].'</span>
                                                                            </td>
                                                                       </tr>';
                                                                    }
                                                                ?>
                                                                </tbody>
                                                            </table>
                                                            <!--end::Table-->
                                                        </div>
                                                    </div>
                                                    <!--end::Card-->
                                                </div>
                                                <!--end::Content-->
                                            </div>
                                            <!--end:: -----------------------------------FINALIZA -------------------------------------->
										</div>
                                        
									</div>
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

                <div class="modal fade" id="kt_modal_new_ticket" tabindex="-1" aria-hidden="true">
                    <!--begin::Modal dialog-->
                    <div class="modal-dialog modal-dialog-centered mw-750px">
                        <!--begin::Modal content-->
                        <div class="modal-content rounded">
                            <!--begin::Modal header-->
                            <div class="modal-header pb-0 border-0 justify-content-end">
                                <!--begin::Close-->
                                <div class="btn btn-sm btn-icon btn-active-color-primary" data-bs-dismiss="modal"><i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i></div>
                                <!--end::Close-->
                            </div>
                            <!--begin::Modal header-->
                            <!--begin::Modal body-->
                            <div class="modal-body scroll-y px-10 px-lg-15 pt-0 pb-15">
                                <!--begin:Form-->
                                <form id="form_ticket" class="form" action="#">
                                    <!--begin::Heading-->
                                    <div class="mb-13 text-center">
                                        <!--begin::Title-->
                                        <h1 class="mb-3">Criar Ticket</h1>
                                        <!--end::Title-->
                                    </div>
                                    <!--end::Heading-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-8 fv-row">
                                        <!--begin::Label-->
                                        <label class="d-flex align-items-center fs-6 fw-semibold mb-2 required">Assunto</label>
                                        <!--end::Label-->
                                        <input type="text" class="form-control form-control-solid" placeholder="Insira o assunto." name="assunto" id="assunto" maxlenght="450"/>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="row g-9 mb-8">
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Do que se trata o ticket?</label>
                                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Selecione do que se trata" name="tipo" id="tipo">
                                                <option value="">Selecione...</option>
                                                <option value="bug">Correção de um erro</option>
                                                <?php echo (($_SESSION['USUTIPO'] == 'QUALIDADE' && $_SESSION['USUADMIN'] == '1') ? '<option value="melhoria">Solicitar uma melhoria do sistema</option>' : '') ?>
                                                <option value="alteracao">Solicitar uma alteração</option>
                                                <option value="outro">Outros</option>
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                        <!--begin::Col-->
                                        <div class="col-md-6 fv-row">
                                            <label class="required fs-6 fw-semibold mb-2">Nível de prioridade</label>
                                            <select class="form-select form-select-solid" data-control="select2" data-hide-search="true" data-placeholder="Selecione a prioridade" name="prioridade" id="prioridade">
                                                <option value="">Selecione...</option>
                                                <option value="1">Baixa (Não impede a operação)</option>
                                                <option value="2">Moderada (Não impede a operação; porém dificulta)</option>
                                                <option value="3">Alta (Impede a operação)</option>
                                            </select>
                                        </div>
                                        <!--end::Col-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="d-flex flex-column mb-8 fv-row">
                                        <label class="fs-6 fw-semibold mb-2 required">Descrição</label>
                                        <textarea class="form-control form-control-solid" maxlenght="1000" rows="4" name="descricao"  id="descricao" placeholder="Entre com a descrição da sua solicitação"></textarea>
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="fv-row mb-8">
                                        <!--begin::Dropzone-->
                                        <div class="form-group row">
                                            <!--begin::Label-->
                                            <label class="col-lg-3 col-form-label text-lg-right">Subir Arquivo:</label>
                                            <!--end::Label-->

                                            <!--begin::Col-->
                                            <div class="col-lg-9">
                                                <!--begin::Dropzone-->
                                                <div class="dropzone dropzone-queue mb-2" id="kt_dropzonejs_example_3">
                                                    <!--begin::Controls-->
                                                    <div class="dropzone-panel mb-lg-0 mb-2">
                                                        <a class="dropzone-select btn btn-sm btn-primary me-2">Anexar arquivo</a>
                                                        <a class="dropzone-remove-all btn btn-sm btn-light-primary">Apagar Todos</a>
                                                    </div>
                                                    <!--end::Controls-->

                                                    <!--begin::Items-->
                                                    <div class="dropzone-items wm-200px">
                                                        <div class="dropzone-item" style="display:none">
                                                            <!--begin::File-->
                                                            <div class="dropzone-file">
                                                                <div class="dropzone-filename" title="some_image_file_name.jpg">
                                                                    <span data-dz-name>some_image_file_name.jpg</span>
                                                                    <strong>(<span data-dz-size>340kb</span>)</strong>
                                                                </div>

                                                                <div class="dropzone-error" data-dz-errormessage></div>
                                                            </div>
                                                            <!--end::File-->

                                                            <!--begin::Progress-->
                                                            <div class="dropzone-progress">
                                                                <div class="progress">
                                                                    <div
                                                                        class="progress-bar bg-primary"
                                                                        role="progressbar" aria-valuemin="0" aria-valuemax="100" aria-valuenow="0" data-dz-uploadprogress>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                            <!--end::Progress-->

                                                            <!--begin::Toolbar-->
                                                            <div class="dropzone-toolbar">
                                                                <span class="dropzone-delete" data-dz-remove><i class="bi bi-x fs-1"></i></span>
                                                            </div>
                                                            <!--end::Toolbar-->
                                                        </div>
                                                    </div>
                                                    <!--end::Items-->
                                                </div>
                                                <!--end::Dropzone-->

                                                <!--begin::Hint-->
                                                <span class="form-text text-muted">O tamaho máximo por arquivo é de 5MB e o número máximo de arquivo é 5.</span>
                                                <!--end::Hint-->
                                            </div>
                                            <!--end::Col-->
                                        </div>
                                        <!--end::Dropzone-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Input group-->
                                    <div class="mb-15 fv-row">
                                        <!--begin::Wrapper-->
                                        <div class="d-flex flex-stack">
                                            <!--begin::Label-->
                                            <div class="fw-semibold me-5">
                                                <label class="fs-6">Notificação</label>
                                                <div class="fs-7 text-gray-400">Receber notificação por email</div>
                                            </div>
                                            <!--end::Label-->
                                            <!--begin::Checkboxes-->
                                            <div class="d-flex align-items-center">
                                                <!--begin::Checkbox-->
                                                <label class="form-check form-check-custom form-check-solid me-10">
                                                    <input class="form-check-input h-20px w-20px" type="checkbox" name="notificacao" id="notificacao" value="email" checked="checked" />
                                                    <span class="form-check-label fw-semibold">Email</span>
                                                </label>
                                                <!--end::Checkbox-->
                                            </div>
                                            <!--end::Checkboxes-->
                                        </div>
                                        <!--end::Wrapper-->
                                    </div>
                                    <!--end::Input group-->
                                    <!--begin::Actions-->
                                    <div class="text-center">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                        <button type="submit" id="ticket_submit" class="btn btn-primary">
                                            <span class="indicator-label">Criar</span>
                                            <span class="indicator-progress">Processando...
                                            <span class="spinner-border spinner-border-sm align-middle ms-2"></span></span>
                                        </button>
                                    </div>
                                    <!--end::Actions-->
                                </form>
                                <!--end:Form-->
                            </div>
                            <!--end::Modal body-->
                        </div>
                        <!--end::Modal content-->
                    </div>
                    <!--end::Modal dialog-->
                </div>

			</div>
			<!--end::Page-->
		</div>
		<!--end::Drawers-->
		<?php require $_SERVER['DOCUMENT_ROOT']."/menu/footer-js.php"; ?>
	</body>
	<!--end::Body-->
</html>
<link href="<?php echo $_SESSION['URL'] ?>/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $_SESSION['URL'] ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>
"use strict";
var KTAppInboxListing = function() {
    var t, n, e = () => {
    };
    return {
        init: function() {
            (t = document.querySelector("#kt_inbox_listing")) && ((n = $(t).DataTable({
                info: !1,
                order: [],
                language: {
                    url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
                },
            })).on("draw", (function() {
                e()
            })), document.querySelector('[data-kt-inbox-listing-filter="search"]').addEventListener("keyup", (function(t) {
                n.search(t.target.value).draw()
            })), e())
        }
    }
}();
KTUtil.onDOMContentLoaded((function() {
    KTAppInboxListing.init()
}));

// set the dropzone container id
const idzone = "#kt_dropzonejs_example_3";
const dropzone = document.querySelector(idzone);

// set the preview element template
var previewNode = dropzone.querySelector(".dropzone-item");
previewNode.id = "";
var previewTemplate = previewNode.parentNode.innerHTML;
previewNode.parentNode.removeChild(previewNode);

var myDropzone = new Dropzone(idzone, { // Make the whole body a dropzone
    url: "handle.php", // Set the url for your upload script location
    parallelUploads: 5,
    maxFilesize: 5, // Max filesize in MB
    previewTemplate: previewTemplate,
    previewsContainer: idzone + " .dropzone-items", // Define the container to display the previews
    clickable: idzone + " .dropzone-select", // Define the element that should be used as click trigger to select files.
    paramName: "fileToUpload"
});


myDropzone.on("addedfile", function (file) {
    // Hookup the start button
    const dropzoneItems = dropzone.querySelectorAll('.dropzone-item');
    dropzoneItems.forEach(dropzoneItem => {
        dropzoneItem.style.display = '';
    });
});

// Update the total progress bar
myDropzone.on("totaluploadprogress", function (progress) {
    const progressBars = dropzone.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
        progressBar.style.width = progress + "%";
    });
});

myDropzone.on("sending", function (file) {
    // Show the total progress bar when upload starts
    const progressBars = dropzone.querySelectorAll('.progress-bar');
    progressBars.forEach(progressBar => {
        progressBar.style.opacity = "1";
    });
});

// Hide the total progress bar when nothing"s uploading anymore
myDropzone.on("complete", function (progress) {
    const progressBars = dropzone.querySelectorAll('.dz-complete');

    progressBars.forEach(progressBar => {
        const progressBarElement = progressBar.querySelector('.progress-bar');
        const progressElement = progressBar.querySelector('.progress');
        
        if (progressBarElement && progressElement) {
            progressBarElement.style.opacity = "0";
            progressElement.style.opacity = "0";
        }
    });
});

var elemento = document.getElementById("corpinho");
elemento.style.display = "block";

var spin = document.getElementById("div_spin");
spin.style.display = "none";


function toggleColor(iconElement) {
    var parentLink = iconElement.parentNode;
    var fav;

    if (parentLink.classList.contains("btn-color-gray-400")) {
        parentLink.classList.remove("btn-color-gray-400");
        parentLink.classList.add("btn-color-warning");
        fav = 1;
    } else {
        parentLink.classList.remove("btn-color-warning");
        parentLink.classList.add("btn-color-gray-400");
        fav = 0;
    }
    $.post({
        url: "handle.php", 
        type: "POST", // HTTP verb
        data: {action: "favorito", id: parentLink.id, favorito: fav},
        success: function (response) {
        }
    });
}


const form = document.getElementById('form_ticket');

// Init form validation rules. For more info check the FormValidation plugin's official documentation:https://formvalidation.io/
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'assunto': {
                validators: {
                    notEmpty: {message: 'Campo obrigatório'}
                }
            },
            'tipo': {
                validators: {
                    notEmpty: {message: 'Campo obrigatório'}
                }
            },
            'prioridade': {
                validators: {
                    notEmpty: {message: 'Campo obrigatório'}
                }
            },
            'descricao': {
                validators: {
                    notEmpty: {message: 'Campo obrigatório'}
                }
            },
        },

        plugins: {
            trigger: new FormValidation.plugins.Trigger(),
            bootstrap: new FormValidation.plugins.Bootstrap5({
                rowSelector: '.fv-row',
                eleInvalidClass: '',
                eleValidClass: ''
            })
        }
    }
);

// Submit button handler
const submitButton = document.getElementById('ticket_submit');
submitButton.addEventListener('click', function (e) {
    // Prevent default button action
    e.preventDefault();

    // Validate form before submit
    if (validator) {
        validator.validate().then(function (status) {
            console.log('validated!');

            if (status == 'Valid') {
                // Show loading indication
                submitButton.setAttribute('data-kt-indicator', 'on');

                // Disable button to avoid multiple click
                submitButton.disabled = true;

                // Simulate form submission. For more info check the plugin's official documentation: https://sweetalert2.github.io/
                setTimeout(function () {
                    // Remove loading indication
                    submitButton.removeAttribute('data-kt-indicator');

                    // Enable button
                    submitButton.disabled = false;
                    
                    var check;
					var checkboxElement = document.getElementById("notificacao");
					if (checkboxElement.checked) {
						check = 1;
					} else {
						check = 0;
					}

                    var files = myDropzone.getQueuedFiles();

                    // Create a FormData object to hold the form data and files
                    var formData = new FormData();
                    formData.append('action', 'gravar');
                    formData.append('assunto', $('#assunto').val());
                    formData.append('tipo', $('#tipo').val());
                    formData.append('prioridade', $('#prioridade').val());
                    formData.append('descricao', $('#descricao').val());
                    formData.append('check', check);

                    // Append the files to the FormData object
                    myDropzone.files.forEach(function(file) {
                        formData.append('files[]', file);
                    });
                    $.post({
						url: "handle.php", // the resource where youre request will go throw
						type: "POST",
                        data: formData,
						file: myDropzone,
                        contentType: false,
                        processData: false, 
						success: function (response) {
							if(response == '1'){
								
								Swal.fire({
									text: "Ticket Cadastrado com sucesso.",
									icon: "success",
									timer: 2000,
								});
								setTimeout(function () {
									location.reload(); 
								}, 500);
								
							}else{
								Swal.fire({
									text: response,
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
            }
        });
    }
});
</script>
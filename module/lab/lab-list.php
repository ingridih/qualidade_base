<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";

    if ( $_SESSION['USUADMIN'] != '1') {
    header('Location: /login'); die;
    }
    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL::getConnection();

    $array_lab = array();
    $Conexao = ConexaoMYSQL::getConnection();
        $query = $Conexao->query("SELECT E_ID, E_NOME, E_ATIVO, E_CRIADOPOR, USU_NOME  
            FROM QUALIDADE_LABORATORIO LEFT JOIN QUALIDADE_USUARIO_LOGIN ON E_CRIADOPOR = USU_ID");
        while ($row = $query->fetch()) {
            $array_lab[] = $row;
        }

    $array_status = array(
        'A' => 'Ativo',
        'A_status' => 'success',
        'I' => 'Inativo',
        'I_status' => 'danger',
    ); 
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
												<span class="card-label fw-bold fs-3 mb-1">Lista de Laboratórios</span>
											</h3>
                                            <div class="card-toolbar">
                                                <a href="lab-add" class="btn btn-sm btn-info" data-bs-toggle="modal" data-bs-target="#kt_modal_lab">Criar Laboratório</a>
                                            </div>
										</div>
                                        
                                        <div class="card-body ">
											<!--begin::Table container-->
											<table id="kt_datatable_fixed_columns" class="table align-middle table-row-dashed fs-7 gy-5">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                        <th class="min-w-200px">Laboratório</th>
                                                        <th class="min-w-150px">Usuário que criou</th>
                                                        <th class="min-w-150px">Status</th>
                                                        <th class="min-w-100px">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach ($array_lab as $lab) {
                                                            echo '<tr>
                                                                <td>'.$lab['E_NOME'].'</td>
                                                                <td>'.$lab['USU_NOME'].'</td>
                                                                <td><span class="badge py-3 px-4 fs-8 badge-light-'.$array_status[$lab['E_ATIVO'].'_status'].'">'.$array_status[$lab['E_ATIVO']].'</span></td>
                                                                <td> <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                                                    Ações
                                                                    <span class="svg-icon fs-5 m-0"><svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                                                    <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd"><polygon points="0 0 24 0 24 24 0 24"></polygon><path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path></g>
                                                                    </svg></span>
                                                                </a>
                                                                <!--begin::Menu-->
                                                                <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-125px py-4" data-kt-menu="true">
                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3 editarlab" id="'.$lab['E_ID'].'" data-id="'.$lab['E_NOME'].'" data-status="'.$lab['E_ATIVO'].'" data-bs-toggle="modal" data-bs-target="#modaledit">
                                                                            Editar
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3 apagarlab" id="'.$lab['E_ID'].'">
                                                                            Apagar
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->
                                                                </div></td>
                                                            </tr>';
                                                        }
                                                    ?>
                                                </tbody>
                                            </table>
											<!--end::Table container-->
										</div>
									</div>
								</div>
								<!--end::Content container-->
							</div>
							<!--end::Content-->
						</div>
						<!--end::Content wrapper-->

                        <div class="modal fade" tabindex="-1" id="kt_modal_lab">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">Cadastrar Laboratório</h3>

                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <!--end::Close-->
                                    </div>

                                    <div class="modal-body">
                                        <input type="text" id="nomelab" class="form-control" placeholder="Insira o nome do laboratório."/>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                        <button type="button" class="btn btn-primary" id="btnlab">
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


                        <div class="modal fade" tabindex="-1" id="modaledit">
                            <div class="modal-dialog">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <h3 class="modal-title">Editar Laboratório</h3>
                                        <!--begin::Close-->
                                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                        </div>
                                        <!--end::Close-->
                                    </div>
                                    <div class="modal-body">
                                        <input type="text" id="nomelab_edit" class="form-control mb-3" placeholder="Alterar o nome do laboratório."/>
                                        <select type="text" id="statuslab_edit" class="form-control">
                                            <option value="A">Ativo</option>
                                            <option value="I">Inativo</option>
                                        </select>
                                    </div>

                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                        <button type="button" class="btn btn-primary" id="btneditlab">
                                            <span class="indicator-label">
                                                Editar
                                            </span>
                                            <span class="indicator-progress">
                                                Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                            </span>
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
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
<link href="<?php echo $_SESSION['URL'] ?>/assets/plugins/custom/datatables/datatables.bundle.css" rel="stylesheet" type="text/css"/>
<script src="<?php echo $_SESSION['URL'] ?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
<script>


$("#kt_datatable_fixed_columns").DataTable({
    language: {
        url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
    },
});


 $(document).on('click','#btnlab',function(){
	$.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "cadastrar-lab", lab: $('#nomelab').val()},
		success: function (response) {
			var result = JSON.parse(response);
			if(result == '1'){
				toastr["success"]("Laboratório criado com sucesso.");
				$('#kt_modal_lab').modal('hide'); 
                window.location.reload(true);
			}else{
				toastr["error"]("Ocorreu um erro. "+result);
			}
		}
	});
});



$(document).on('click','.apagarlab',function(){
    Swal.fire({
        title: 'Tem certeza que deseja apagar esse laboratório?',
        text: "Os usuários que pertencerem a ele serão apagados também.",
        icon: 'warning',
        showCancelButton: true,

        confirmButtonText: 'Sim, Apagar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post({
                url: "handle.php", // the resource where youre request will go throw
                type: "POST", // HTTP verb
                data: {action: "apagar-lab", id: $(this).attr('id')},
                success: function (response) {
                    var result = JSON.parse(response);
                    if(result == '1'){
                        Swal.fire(
                        'Apagado!',
                        'Registro apagado com sucesso.',
                        'success'
                        );
                        setTimeout(function () {
                            window.location.reload(true);
                        }, 1000);
                        
                    }else{
                        Swal.fire(
                        'Erro!',
                        'Não foi possivel apagar registro. '+response,
                        'error'
                        );
                    }
                }
            });
        }
    })
});


var editid;
$(document).on('click','.editarlab',function(){
    editid = $(this).attr('id');
    document.getElementById("nomelab_edit").value = $(this).attr('data-id');
    document.getElementById("statuslab_edit").value = $(this).attr('data-status');
});
$(document).on('click','#btneditlab',function(){
	$.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "editar-lab", id: editid, nome: $('#nomelab_edit').val(), status: $('#statuslab_edit').val()},
		success: function (response) {
			var result = JSON.parse(response);
			if(result == '1'){
				toastr["success"]("Laboratório editado com sucesso.");
				$('#modaledit').modal('hide'); 
                window.location.reload(true);
			}else{
				toastr["error"]("Ocorreu um erro. "+result);
			}
		}
	});
});

</script>
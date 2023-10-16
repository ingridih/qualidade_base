<?php 

    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";

    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL::getConnection();

    $array_usu = array();
    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT USU_NOME, USU_EMAIL, USU_ATIVO,  USU_EMPRESA_NOME, E_NOME, USU_TIPO, USU_ID  
        FROM QUALIDADE_USUARIO_LOGIN
        LEFT JOIN QUALIDADE_LABORATORIO ON USU_EMPRESAID = E_ID AND USU_TIPO = 'LABORATORIO'");
    while ($row = $query->fetch()) {
        $array_usu[] = $row;
    }

    $array_status = array(
        'A' => 'Ativo',
        'A_status' => 'success',
        'I' => 'Inativo',
        'I_status' => 'danger',
    ); 

    $array_lab = array(
        'QUALIDADE' => 'warning',
        'LABORATORIO' => 'danger',
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
												<span class="card-label fw-bold fs-3 mb-1">Lista de Usuários</span>
											</h3>
                                            <div class="card-toolbar">
                                                <a href="user-add" class="btn btn-sm btn-info">Criar Usuário</a>
                                            </div>
										</div>
                                        
                                        <div class="card-body ">
											<!--begin::Table container-->
											<table id="kt_datatable_fixed_columns" class="table align-middle table-row-dashed fs-7 gy-5">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                        <th class="min-w-200px">Nome</th>
                                                        <th class="min-w-150px">Email</th>
                                                        <th class="min-w-150px">Empresa</th>
                                                        <th class="min-w-100px">Tipo</th>
                                                        <th class="min-w-100px">Status</th>
                                                        <th class="min-w-100px">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach ($array_usu as $ar) {
                                                            echo '<tr>
                                                                <td>'.$ar['USU_NOME'].'</td>
                                                                <td>'.$ar['USU_EMAIL'].'</td>
                                                                <td>'.(($ar['E_NOME'] == "") ? $ar['USU_EMPRESA_NOME'] : $ar['E_NOME']).'</td>
                                                                <td><span class="badge py-3 px-4 fs-8 badge-light-'.$array_lab[$ar['USU_TIPO']].'">'.$ar['USU_TIPO'].'</span></td>
                                                                <td><span class="badge py-3 px-4 fs-8 badge-light-'.$array_status[$ar['USU_ATIVO'].'_status'].'">'.$array_status[$ar['USU_ATIVO']].'</span></td>
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
                                                                        <a href="user-add?id='.base64_encode($ar['USU_ID']).'" class="menu-link px-3" data-kt-docs-table-filter="edit_row">
                                                                            Editar
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3 apagarusu" data-kt-docs-table-filter="delete_row" id="'.$ar['USU_ID'].'">
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
    "dom":
		"<'row'" +
		"<'col-sm-6 d-flex align-items-center justify-conten-start'l>" +
		"<'col-sm-6 d-flex align-items-center justify-content-end'f>" +
		">" +

		"<'table-responsive'tr>" +

		"<'row'" +
		"<'col-sm-12 col-md-5 d-flex align-items-center justify-content-center justify-content-md-start'i>" +
		"<'col-sm-12 col-md-7 d-flex align-items-center justify-content-center justify-content-md-end'p>" +
		">"

});



$(document).on('click','.apagarusu',function(){
    Swal.fire({
        title: 'Tem certeza que deseja apagar esse usuário?',
        text: "Os usuários que tiverem interagido em outras análises, poderá perder o histórico de interação.",
        icon: 'warning',
        showCancelButton: true,
        confirmButtonText: 'Sim, Apagar'
    }).then((result) => {
        if (result.isConfirmed) {
            $.post({
                url: "handle.php", // the resource where youre request will go throw
                type: "POST", // HTTP verb
                data: {action: "apagar-usuario", id: $(this).attr('id')},
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

</script>
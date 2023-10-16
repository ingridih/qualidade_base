<?php 

    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL::getConnection();

    $array_usu = array();
    $Conexao = ConexaoMYSQL::getConnection();
    $query = $Conexao->query("SELECT USU_NOME, USU_EMAIL, USU_ATIVO,  USU_EMPRESA_NOME, E_NOME, USU_TIPO, USU_ID  
        FROM QUALIDADE_USUARIO_LOGIN
        LEFT JOIN QUALIDADE_LABORATORIO ON USU_EMPRESAID = E_ID AND USU_TIPO = 'LABORATORIO' WHERE USU_EMPRESAID = '".$_SESSION['USUEMPID']."'");
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
                                                                <td><button class="btn btn-light-danger btn-sm senha" id="'.$ar['USU_ID'].'">
                                                                    <span class="indicator-label">
                                                                        <i class="fa-solid fa-key"></i> Nova Senha</a>
                                                                    </span>
                                                                    <span class="indicator-progress">
                                                                        Enviando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                                    </span>
                                                                </button>
                                                                </td>
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

$(document).on('click', '.senha', function () {
    var button = $(this); // Seleciona o botão clicado
    button.attr("data-kt-indicator", "on"); // Define o atributo no botão clicado
    var id = button.attr("id"); // Obtém o ID do botão clicado
    
    $.post({
        url: "handle.php",
        type: "POST",
        data: { action: "gerar_senha", id: id },
        success: function (response) {
            if (response == '1') {
                Swal.fire({
                    text: "Senha enviada por email.",
                    icon: "success",
                    timer: 2000,
                });
            } else {
                Swal.fire({
                    text: "Erro ao tentar enviar email com senha, verifique se o email está correto ou informe o contratante.",
                    icon: "error",
                    buttonsStyling: false,
                    confirmButtonText: "Ok",
                    customClass: {
                        confirmButton: "btn btn-primary"
                    }
                });
            }
            button.removeAttr("data-kt-indicator"); // Remove o atributo do botão clicado
        }
    });
});


</script>
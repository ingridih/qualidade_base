<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
$Conexao = ConexaoMYSQL::getConnection();

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
                                                            Criar um novo método.
                                                        </h1>
                                                        <form class="row g-3" id="form">
															<div class="form-group row mb-5 fv-row">
																<div class="col-md-6">
																	<label for="nome" class="form-label">Método de análise</label>
																	<input type="text" class="form-control" id="metodo" name="metodo" placeholder="Insira o nome do método">
																</div>
															</div>
															<div class="form-group row mb-5">
																<div class="col-md-12">
																	<button id="btn-salvar" type="submit" class="btn btn-primary  btn-salvar">
																		<span class="indicator-label">
																			Cadastrar Método
																		</span>
																		<span class="indicator-progress">
																			Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
																		</span>
																	</button>
																</div>
															</div>
                                                            <hr>

                                                            <table id="kt_datatable_fixed_columns" class="table align-middle table-row-dashed fs-7 gy-5">
                                                                <thead>
                                                                    <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                                        <th class="min-w-100px">ID</th>
                                                                        <th class="min-w-250px">Método</th>
                                                                        <th class="min-w-250px">Usuário</th>
                                                                        <th class="min-w-100px" style="text-align:center">Ações</th>
                                                                    </tr>
                                                                </thead>
                                                                <tbody>
                                                                    <?php 
                                                                        $query = $Conexao->query("SELECT MA_ID, MA_METODO, MA_USUARIO, MA_DATA, USU_NOME FROM METODO_ANALISE
                                                                        LEFT JOIN QUALIDADE_USUARIO_LOGIN ON USU_ID = MA_USUARIO");
                                                                        while ($row = $query->fetch()) {
                                                                            echo '<tr>
                                                                                <td>'.$row['MA_ID'].'</td>
                                                                                <td>'.$row['MA_METODO'].'</td>
                                                                                <td>'.$row['USU_NOME'].'</td>
                                                                                <td><a href="#" class="btn btn-light-danger btn-sm delete" id="'.$row['MA_ID'].'"><i class="fa-solid fa-trash"></i> Apagar</a></td>
                                                                            </tr>';
                                                                        }
                                                                    ?>
                                                                </tbody>
                                                            </table>
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

});


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



const form = document.getElementById("form");

var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            "metodo": {
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

					$.post({
						url: "handle.php", // the resource where youre request will go throw
						type: "POST", // HTTP verb
						data: {action: "cadastrar-metodo", metodo: $('#metodo').val()}, 
						success: function (response) {
							if(response == '1'){
								
								Swal.fire({
									text: "Método Cadastrado com Sucesso.",
									icon: "success",
									buttonsStyling: false,
									confirmButtonText: "Ok",
									customClass: {
										confirmButton: "btn btn-primary"
									}
								}).then((result) => {
  									if (result.isConfirmed) {
										window.location.reload();
									}
								})
								
							}else if(response == '2'){
								toastr["error"]("Ocorreu um erro. "+response);
							}else if(response == '3'){
								Swal.fire({
									text: "Esse método já foi cadastrado.",
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


$(document).on('click','.delete',function(){
    Swal.fire({
        title: 'Atenção.',
        text: "Tem certeza que deseja apagar esse método?",
        icon: 'warning',
        showCancelButton: true,

        confirmButtonText: 'Sim, Apagar'
    }).then((result) => {
    if (result.isConfirmed) {
        $.post({
            url: "handle.php", // the resource where youre request will go throw
            type: "POST", // HTTP verb
            data: {action: "apagar-metodo", id: $(this).attr('id')},
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
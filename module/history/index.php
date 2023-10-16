<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL::getConnection();
    
    $array_status = array(
        '1' => 'Análise Criado',
        '2' => 'Análise Analisada',
        '3' => 'Análise Finalizada',
        '11' => 'Análise Cancelada',
        '12' => 'Análise Editada',
        '13' => 'Analise. Resultado editado',
        '4' => 'Laudo Alterado',
        '14' => 'Laudo configurado',
        '5' => 'Usuário Criado',
        '6' => 'Usuário Editado',
        '7' => 'Usuário Apagado',
        '8' => 'Laboratório Cadastrado',
        '9' => 'Laboratório Apagado',
        '10' => 'Elemento Cadastrado',
        '15' => 'Laudo anexado',
        '16' => 'Laudo apagado'
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
												<span class="card-label fw-bold fs-3 mb-1">Histórico de Atividades. </span>
											</h3>
										</div>
                                        <div class="card-body ">
                                            <div class="alert alert-primary d-flex align-items-center p-5">
                                                <!--begin::Icon-->
                                                <i class="fa-solid fa-triangle-exclamation fa-fw  fs-2hx text-primary me-4"></i>
                                                <div class="d-flex flex-column">
                                                    <!--begin::Title-->
                                                    <h4 class="mb-1 text-primary">Lembrete!</h4>
                                                    <span>O histórico será apagado com frequencia. Os registros antes do dia <?php echo date('d/m/Y', strtotime('-120 days', strtotime(date('d/m/Y')))) ?> serão apagados.</span>
                                                </div>
                                                <!--end::Wrapper-->
                                            </div>
											<!--begin::Table container-->
											<table id="kt_table" class="table align-middle table-row-dashed fs-7 gy-5">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                        <th class="min-w-200px">Nome</th>
                                                        <th class="min-w-150px">Status</th>
                                                        <th class="min-w-100px">Descrição</th>
                                                        <th class="min-w-100px">Data</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        $query = $Conexao->query("SELECT H_ID, H_USUID, H_USUNOME, H_TIPO, H_DESCRICAO, DATE_FORMAT(H_DATA,'%d/%m/%Y') H_DATA
                                                        FROM HISTORICO ORDER BY H_DATA DESC");
                                                        while ($row = $query->fetch()) {
                                                  
                                                        echo '<tr>
                                                            <td>'.$row['H_USUNOME'].'</td>
                                                            <td>'.$array_status[$row['H_TIPO']].'</td>
                                                            <td>'.$row['H_DESCRICAO'].'</td>
                                                            <td>'.$row['H_DATA'].'</td>
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


var datatable_elementos = function() {
    var e, t, n, r, o, a = (e) => {
            r = e[3] ? new Date(e[3]) : null, o = e[3] ? new Date(e[3]) : null, $.fn.dataTable.ext.search.push((function(e, t) {
                var l = new Date(moment($(t[6]).text(), "DD/MM/YYYY"));
                return (null === r || r <= l) && (null === o || o >= l);
            })), t.draw();
        },
        c = () => {
           
        };
    return {
        init: function() {
            (e = document.querySelector("#kt_table")) && ((t = $(e).DataTable({
                info: !1,
                order: [],
                pageLength: 10,
                columnDefs: [{
                }, {
                }],
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
                ">",
                lengthMenu: [[10, 25, 50, -1], [10, 25, 50, 'Tudo']],
                // Defina 'All' como a quantidade de registros por página padrão
                pageLength: 25
            })).on("draw", (function() {
                c();
            })), c());
        }
    };
}();

KTUtil.onDOMContentLoaded((function() {
    datatable_elementos.init();
}));

const form = document.getElementById('form_criar');
var validator = FormValidation.formValidation(
    form,
    {
        fields: {
            'sigla': {
                validators: {
                    notEmpty: {message: 'Campo Obrigatório.'}
                }
            },
            'nome': {
                validators: {
                    notEmpty: {message: 'Campo Obrigatório.'}
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
const submitButton = document.getElementById('criarelemento');
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
                    

                    $.post({
                        url: "handle.php", // the resource where youre request will go throw
                        type: "POST", // HTTP verb
                        data: {action: "cadastrar-elemento", sigla: $('#sigla').val(), nome: $('#nome').val(), tipo: $('#tipo').val(), vma: $('#vma').val()},
                        success: function (response) {
                            var result = JSON.parse(response);
                            if(result == '1'){
                                toastr["success"]("Elemento criado com sucesso.");
                                $('#modal_criarelemento').modal('hide'); 
                                setTimeout(function () {
                                    window.location.reload(true);
                                }, 1000);
                            }else{
                                toastr["error"]("Ocorreu um erro. "+result);
                            }
                        }
                        
                    });
                    submitButton.removeAttribute('data-kt-indicator');
                    // Enable button
                    submitButton.disabled = false;
                    //form.submit(); // Submit form
                }, 2000);
            }
        });
    }
});

var editid;
$(document).on('click','.editar',function(){
    editid = $(this).attr('id');
    $.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "busca-elemento", id: editid},
		success: function (response) {
			var result = JSON.parse(response);
			if(result != '2'){
                document.getElementById("nomeedit").value = result.elemento;
                document.getElementById("siglaedit").value = result.sigla;
                document.getElementById("tipoedit").value = result.tipo;
                document.getElementById("vmaedit").value = result.vma;
			}else{
				toastr["error"]("Ocorreu um erro. "+result);
			}
		}
	});
});


$(document).on('click','#btn-editar',function(){
    const submitButton2 = document.getElementById('btn-editar');
    submitButton2.setAttribute('data-kt-indicator', 'on');
    // Disable button to avoid multiple click
    submitButton2.disabled = true;
	$.post({
		url: "handle.php", // the resource where youre request will go throw
		type: "POST", // HTTP verb
		data: {action: "editar-elemento", id: editid, sigla: $('#siglaedit').val(), nome: $('#nomeedit').val(), tipo: $('#tipoedit').val(), vma: $('#vmaedit').val()},
		success: function (response) {
			var result = response;
			if(result == '1'){
				toastr["success"]("Elemento editado com sucesso.");
				$('#modaledit').modal('hide'); 
                window.location.reload(true);
			}else{
				toastr["error"]("Ocorreu um erro. "+result);
			}
            submitButton2.removeAttribute('data-kt-indicator');
            // Enable button
            submitButton2.disabled = false;
        }
	});
});


$(document).on('click','.apagar',function(){
    Swal.fire({
        title: 'Tem certeza que deseja apagar esse elemento?',
        text: "Pode ser que perca algum histórico se fizer isso.",
        icon: 'warning',
        showCancelButton: true,

        confirmButtonText: 'Sim, Apagar'
    }).then((result) => {
    if (result.isConfirmed) {
        $.post({
            url: "handle.php", // the resource where youre request will go throw
            type: "POST", // HTTP verb
            data: {action: "apagar-elemento", id: $(this).attr('id')},
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
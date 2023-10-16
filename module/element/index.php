<?php 
    require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";
    require_once $_SERVER['DOCUMENT_ROOT'].'/pdo/pdomysql.php';
    $Conexao = ConexaoMYSQL::getConnection();

    $array_elemento = array();
    $Conexao = ConexaoMYSQL::getConnection();
        $query = $Conexao->query("SELECT EQ_ID, EQ_NOME, EQ_SIGLA, DATE_FORMAT(EQ_DATA, '%d/%m/%Y %H:%i') AS EQ_DATA2, EQ_USUARIO, EQ_TIPO, EQ_VMA, USU_NOME, EQ_DATA
            FROM QUALIDADE_ELEMENTOS_QUIMICOS LEFT JOIN QUALIDADE_USUARIO_LOGIN ON EQ_USUARIO = USU_ID ORDER BY EQ_ID DESC");
        while ($row = $query->fetch()) {
            $array_elemento[] = $row;
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
												<span class="card-label fw-bold fs-3 mb-1">Lista de Elementos</span>
											</h3>
                                            <div class="card-toolbar">
                                                <a href="#" data-bs-toggle="modal" data-bs-target="#modal_criarelemento" class="btn btn-sm btn-info">Criar Elemento</a>
                                            </div>
										</div>
                                        
                                        <div class="card-body ">
											<!--begin::Table container-->
											<table id="kt_table" class="table align-middle table-row-dashed fs-7 gy-5">
                                                <thead>
                                                    <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                        <th class="min-w-200px">Nome</th>
                                                        <th class="min-w-150px">Sigla</th>
                                                        <th class="min-w-100px">Tipo</th>
                                                        <th class="min-w-100px">VMA</th>
                                                        <th class="min-w-100px">Usuário</th>
                                                        <th class="min-w-100px">Criação/Alteração</th>
                                                        <th class="min-w-100px">Ações</th>
                                                    </tr>
                                                </thead>
                                                <tbody>
                                                    <?php 
                                                        foreach ($array_elemento as $el) {
                                                            if($el['EQ_TIPO'] == 'DEFAULT'){
                                                                $tipo = '<span style="font-weight: 600;color: #009ef7;">NORMAL</span>';
                                                            }else if($el['EQ_TIPO'] == 'MP'){
                                                                $tipo = '<span style="font-weight: 600;color: #f1416c;">METAL PESADO</span>';
                                                            }else{
                                                                $tipo = $el['EQ_TIPO'];
                                                            }
                                                            echo '<tr>
                                                                <td>'.trim($el['EQ_NOME']).'</td>
                                                                <td>'.trim($el['EQ_SIGLA']).'</td>
                                                                <td>'.$tipo.'</td>
                                                                <td>'.$el['EQ_VMA'].'</td>
                                                                <td>'.$el['USU_NOME'].'</td>
                                                                <td data-order="'.$el['EQ_DATA'].'">'.$el['EQ_DATA2'].'</td>
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
                                                                        <a href="#" class="menu-link px-3 editar" data-bs-toggle="modal" data-bs-target="#modal_editarelemento" id="'.$el['EQ_ID'].'">
                                                                            Editar
                                                                        </a>
                                                                    </div>
                                                                    <!--end::Menu item-->

                                                                    <!--begin::Menu item-->
                                                                    <div class="menu-item px-3">
                                                                        <a href="#" class="menu-link px-3 apagar" id="'.$el['EQ_ID'].'">
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

                            <div class="modal fade" tabindex="-1" id="modal_criarelemento">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Criar Elemento</h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">
                                            <form id="form_criar" class="form" action="#" autocomplete="off">
                                                <div class="row mb-3 ">
                                                    <div class="col fv-row">
                                                        <input type="text" class="form-control" id="sigla" name="sigla" placeholder="Sigla">
                                                    </div>
                                                    <div class="col fv-row">
                                                        <input type="text" class="form-control" id="nome" name="nome" placeholder="Elemento">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <select type="text" id="tipo" class="form-control">
                                                            <option value="DEFAULT">NORMAL</option>
                                                            <option value="MP">METAL PESADO</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="vma" name="vma" placeholder="VMA">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-primary" id="criarelemento">
                                                <span class="indicator-label">
                                                    Criar Novo
                                                </span>
                                                <span class="indicator-progress">
                                                    Processando... <span class="spinner-border spinner-border-sm align-middle ms-2"></span>
                                                </span>
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            </div>


                            <div class="modal fade" tabindex="-1" id="modal_editarelemento">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h3 class="modal-title">Editar Elemento</h3>

                                            <!--begin::Close-->
                                            <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                                                <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                                            </div>
                                            <!--end::Close-->
                                        </div>

                                        <div class="modal-body">
                                            <form id="form_criar" class="form" action="#" autocomplete="off">
                                                <div class="row mb-3 ">
                                                    <div class="col fv-row">
                                                        <input type="text" class="form-control" id="siglaedit" name="siglaedit" placeholder="Sigla">
                                                    </div>
                                                    <div class="col fv-row">
                                                        <input type="text" class="form-control" id="nomeedit" name="nomeedit" placeholder="Elemento">
                                                    </div>
                                                </div>
                                                <div class="row mb-3">
                                                    <div class="col">
                                                        <select type="text" id="tipoedit" class="form-control">
                                                            <option value="DEFAULT">NORMAL</option>
                                                            <option value="MP">METAL PESADO</option>
                                                        </select>
                                                    </div>
                                                    <div class="col">
                                                        <input type="text" class="form-control" id="vmaedit" name="vmaedit" placeholder="VMA">
                                                    </div>
                                                </div>
                                            </form>
                                        </div>

                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                                            <button type="button" class="btn btn-warning" id="btn-editar">
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
            r = e[5] ? new Date(e[5]) : null, o = e[5] ? new Date(e[5]) : null, $.fn.dataTable.ext.search.push((function(e, t) {
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
                    orderable: !1,
                    targets: 0
                }, {
                    orderable: !1,
                    targets: -1
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
<?php 

require_once $_SERVER['DOCUMENT_ROOT']."/menu/session.php";


if($_SESSION['USUTIPO'] == 'QUALIDADE'){ 
    header('Location: /login'); 
}


if(isset($_GET['data'])){
    $data = $_GET['data'];
}else{
    $data = date('d/m/Y', strtotime('-30 days')).' até '.date('d/m/Y', strtotime('+1 days'));
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
									<!--begin::Row-->
									<div class="row g-5 g-xl-10 mb-5 mb-xl-10">
										<!--begin::Col-->
										<div class="col-xxl-12">
											<!--begin::Engage widget 10-->
											<div class="card card-flush h-md-100">
												<!--begin::Body-->
												<div class="card-body d-flex flex-column justify-content-between mt-9 bgi-no-repeat bgi-size-cover bgi-position-x-center pb-0">
													<!--begin::Wrapper-->
													<div class="mb-10">
														<!--begin::Title-->
														<h1 class="anchor fw-bold mb-5">
                                                            Análises
                                                        </h1>
                                                        <div class="card-header align-items-center py-5 gap-2">
                                                            <!--begin::Card title-->
                                                            <div class="card-title">
                                                                <!--begin::Search-->
                                                                <div class="d-flex align-items-center position-relative my-1">
                                                                    <i class="ki-duotone ki-magnifier fs-1 position-absolute ms-6"><span class="path1"></span><span class="path2"></span></i>
                                                                    <input type="text" data-kt-docs-table-filter="search" style="margin-right: 10px;" class="form-control form-control-solid w-250px ps-15" placeholder="Buscar"/>
                                                                </div>
                                                                <!--end::Search-->
                                                            </div>
                                                            <!--end::Card title-->
                                                            <!--begin::Card toolbar-->
                                                            <div class="card-toolbar flex-row-fluid gap-5">
                                                                <!--begin::Flatpickr-->
                                                                <div class="input-group w-250px">
                                                                    <input type="text" data-kt-docs-table-filter="date" class="form-control form-control-solid w-250px ps-10 flatpicker data" placeholder="Data de Envio" value="<?php echo $data?>"/>
                                                                </div>
                                                                <!--end::Add product-->
                                                            </div>
                                                            <!--end::Card toolbar-->
                                                        </div>
                                                     
                                                        <table id="kt_datatable_example_1" class="table align-middle table-row-dashed fs-7 gy-5">
                                                            <thead>
                                                            <tr class="text-start text-gray-400 fw-bold fs-8 text-uppercase gs-0">
                                                                <th>ID</th>
                                                                <th>Carta</th>
                                                                <th>Observação</th>
                                                                <th>Urgência</th>
                                                                <th>Data de Envio</th>
                                                                <th>Solicitante</th>
                                                                <th>Status</th>
                                                                <th class="text-end min-w-100px">Ações</th>
                                                                <th class="hidden"></th>
                                                            </tr>
                                                            </thead>
                                                            <tbody class="text-gray-600 fw-semibold">
                                                            </tbody>
                                                        </table>
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

        <div class="modal fade" tabindex="-1" id="modal_prod">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">Produtos</h5>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-2x"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-body" id="div_produtos">
                        
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>


        <div class="modal fade" tabindex="-1" id="modal_config">
            <div class="modal-dialog  modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Configurar Emissão do Laudo</h3>

                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>

                    <div class="modal-body" id="div_config_laudo">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                        <button type="button" class="btn btn-primary btn-salvar-config-laudo">Salvar</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" tabindex="-1" id="modal_baixar_laudo">
            <div class="modal-dialog ">
                <div class="modal-content">
                    <div class="modal-header">
                        <h3 class="modal-title">Baixar Laudo</h3>
                        <!--begin::Close-->
                        <div class="btn btn-icon btn-sm btn-active-light-primary ms-2" data-bs-dismiss="modal" aria-label="Close">
                            <i class="ki-duotone ki-cross fs-1"><span class="path1"></span><span class="path2"></span></i>
                        </div>
                        <!--end::Close-->
                    </div>
                    <div class="d-flex justify-content-center">
                        <div class="spinner-border2 text-primary" role="status">
                            <span class="visually-hidden">Loading...</span>
                        </div>
                    </div>
                    <div class="modal-body" id="div_modal_baixar_laudo">
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Fechar</button>
                    </div>
                </div>
            </div>
        </div>

		<!--end::Drawers-->
		<?php require $_SERVER['DOCUMENT_ROOT']."/menu/footer-js.php"; ?>
        <script src="<?php echo  $_SESSION['URL']?>/assets/plugins/custom/datatables/datatables.bundle.js"></script>
        <script src="<?php echo  $_SESSION['URL']?>/assets/js/flatpicker-pt.js"></script>


	</body>
	<!--end::Body-->
</html>
<style>
    .hidden {
        display: none;
    }


.dataTables_processing{
    background-color:white !important;
}
.badge-tipo {
    max-width: 120px;
    width: 120px;
}

</style>
<script>

$(".flatpicker").flatpickr({
    dateFormat: "d/m/Y",
    mode: "range",
    locale: "pt",
    rangeSeparator: ' até ',
    
});

$('.data').on('change', function() {
    var selectedDate = $(this).val();
    var hasUntilKeyword = selectedDate.toLowerCase().includes('até');
    if (hasUntilKeyword) {
        window.location.href = "analysis?data="+selectedDate;
    } 
});

"use strict";

// Class definition
var KTDatatablesServerSide = function () {
    // Shared variables
    var table;
    var dt;
    var filterPayment;

    // Private functions
    var initDatatable = function () {
        dt = $("#kt_datatable_example_1").DataTable({
            language: {
                url: '//cdn.datatables.net/plug-ins/1.13.4/i18n/pt-BR.json',
            },
            processing: true,
            serverSide: true,
            order: [[0, 'desc']],
            stateSave: false,
            searchDelay: 500,
            
            ajax: {
                url: "analysis-list.php?data="+$('.data').val(),
            },
            columns: [
                { data: 'id' },
                { data: 'idenficacao' },
                { data: 'tipo' },
                { data: 'urgencia' },
                { data: 'data', render: function(data, type, row) {
                    var formattedDate = moment(data, "DD/MM/YYYY").format("YYYY-MM-DD");
                    return `<span data-order="${formattedDate}">${data}</span>`;
                }},
                { data: 'solicitante' },
                { data: 'status' },
                { data: 'id' },
                { data: 'usu', className: 'hidden'},
            ],
            columnDefs: [
                {
                    targets: 0,
                    render: function (data, type, row) {
                        var color;
                        if(row.contra != null){
                            color = 'style="color: #ffc700;"';
                        }
                        return `<button type="button" class="btn btn-sm btn-icon btn-light btn-active-light-primary toggle h-25px w-25px produtos" data-bs-toggle="modal" data-bs-target="#modal_prod" id="${data}"
                            data-kt-docs-datatable-subtable="expand_row">
                        <i class="ki-duotone ki-plus fs-3 m-0 toggle-off"></i></button> <span ${color}>${data}</span>`;
                    }
                },
                {
                    targets: 1,
                    render: function (data, type, row) {
                        if(row.idenficacao_lab){
                            return `${data}<br><span style="color: #009ef7;">${row.idenficacao_lab}</span>`;
                        }else{
                            return data;
                        }
                        
                    }
                },
                {
                    targets: 2,
                    render: function (data, type, row) {
                        const valores = data.split('|');
                        let retorno = ''; 
                        
                        for (let i = 0; i < valores.length; i++) {
                            const valor = valores[i];
                            
                            // Condição: se o valor for "valor2", imprime de forma diferente
                            if (valor === "MP") {
                                retorno += '<span class="badge-tipo badge badge-danger">Matéria Prima</span><br>';
                            } else if (valor === "PA") {
                                retorno += '<span class="badge-tipo  badge badge-primary">Produtos Acabado</span><br>';
                            }else if (valor === "MICRO") {
                                retorno += '<span class="badge-tipo badge badge-success">Micronutriente</span><br>';
                            }else{
                                retorno = '';
                            }
                        }
                        return retorno;
                    }
                },
                {
                    targets: 3,
                    className: 'text-center',
                    render: function (data, type, row) {
                        if(data == '1'){
                            return `<i class="fa-solid fa-circle-exclamation fa-fw fs-4" style="color:#f1416c"></i>`;
                        }else {
                            return '';
                        }
                    }
                },
                {
                    targets: 6,
                    className: 'text-center',
                    render: function (data, type, row) {
                        if(data == 'A'){
                            return `<span class="badge py-2 px-7 fs-7 badge-primary">Aguardando</span>`;
                        }else if(data == 'B'){
                            if(row.laudo != ''){
                                return `<span class="badge py-2 px-7 fs-7 badge-info">Analisado</span>`;
                            }else{
                                return `<span class="badge py-2 px-7 fs-7 badge-info" data-bs-toggle="tooltip" data-bs-placement="top" title="Configurar Laudo">Analisado</span>
                                <br><span class="badge py-1 px-7 fs-8 badge-danger">Sem Laudo</span>`;


                            }
                        }else if(data == 'F'){
                            return `<span class="badge py-2 px-7 fs-7 badge-secondary">Finalizado</span>`;
                        }else {
                            return '';
                        }
                    }
                },
                
                {
                    targets: -2,
                    data: null,
                    orderable: false,
                    className: 'text-end',
                    render: function (data, type, row) {
                        var btn = "";
                            
                            if(row.status == 'A'){
                            btn += `
                                <div class="menu-item px-3">
                                    <a href="result?id=${data}" class="menu-link px-3 analisar" id="${data}">
                                        Analisar
                                    </a>
                                </div>`;
                            }
                            if(row.status == 'B'){
                            btn += `
                                <div class="menu-item px-3">
                                    <a href="edit-result?id=${data}" class="menu-link px-3 " id="${data}">
                                        Editar Resultado
                                    </a>
                                </div>`;
                                btn += `
                                <div class="menu-item px-3">
                                    <a href="" id="${data}" data-bs-toggle="modal" data-bs-target="#modal_config" class="menu-link px-3 config-laudo">
                                        Configurar Laudo
                                    </a>
                                </div>`;
                            }
                            if(row.status != 'A' && row.laudo != ''){
                                btn += `
                                <div class="menu-item px-3">
                                    <a href="" id="${data}" data-bs-toggle="modal" data-bs-target="#modal_baixar_laudo" class="menu-link px-3 carrega_baixar_laudo">
                                        Baixar Laudo
                                    </a>
                                </div>`;
                            }
                            btn += `<div class="menu-item px-3">
                                        <a href="#" id="${data}" class="menu-link px-3 baixarct">
                                            Baixar Carta PDF
                                        </a>
                                    </div>`;

                        return `
                            <a href="#" class="btn btn-light btn-active-light-primary btn-sm" data-kt-menu-trigger="click" data-kt-menu-placement="bottom-end" data-kt-menu-flip="top-end">
                                Ações
                                <span class="svg-icon fs-5 m-0">
                                    <svg xmlns="http://www.w3.org/2000/svg" xmlns:xlink="http://www.w3.org/1999/xlink" width="24px" height="24px" viewBox="0 0 24 24" version="1.1">
                                        <g stroke="none" stroke-width="1" fill="none" fill-rule="evenodd">
                                            <polygon points="0 0 24 0 24 24 0 24"></polygon>
                                            <path d="M6.70710678,15.7071068 C6.31658249,16.0976311 5.68341751,16.0976311 5.29289322,15.7071068 C4.90236893,15.3165825 4.90236893,14.6834175 5.29289322,14.2928932 L11.2928932,8.29289322 C11.6714722,7.91431428 12.2810586,7.90106866 12.6757246,8.26284586 L18.6757246,13.7628459 C19.0828436,14.1360383 19.1103465,14.7686056 18.7371541,15.1757246 C18.3639617,15.5828436 17.7313944,15.6103465 17.3242754,15.2371541 L12.0300757,10.3841378 L6.70710678,15.7071068 Z" fill="currentColor" fill-rule="nonzero" transform="translate(12.000003, 11.999999) rotate(-180.000000) translate(-12.000003, -11.999999)"></path>
                                        </g>
                                    </svg>
                                </span>
                            </a>
                            <!--begin::Menu-->
                            <div class="menu menu-sub menu-sub-dropdown menu-column menu-rounded menu-gray-600 menu-state-bg-light-primary fw-bold fs-7 w-150px py-4" data-kt-menu="true">${btn}</div>`
                        ;
                    },
                },
            ],
            // Add data-filter attribute
           
        });

        table = dt.$;

        // Re-init functions on every table re-draw -- more info: https://datatables.net/reference/event/draw
        dt.on('draw', function () {
            KTMenu.createInstances();
        });
    }

    // Search Datatable --- official docs reference: https://datatables.net/reference/api/search()
    var handleSearchDatatable = function () {
        const filterSearch = document.querySelector('[data-kt-docs-table-filter="search"]');
        filterSearch.addEventListener('keyup', function (e) {
            var searchValue = $(this).val();

            // Realiza a busca no DataTable
            dt.search(searchValue).draw();
        });
    }
   

    // Public methods
    return {
        init: function () {
            initDatatable();
            handleSearchDatatable();
        }
    }
}();

KTUtil.onDOMContentLoaded(function () {
    KTDatatablesServerSide.init();
});


$('body').on('click', '.baixarct',
    function(e) {
    var id = $(this).attr("id");
        $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'gerarct', id_ct: id},
        success: function (response) {
            var a = document.createElement('a');
            a.href = '<?php echo  $_SESSION['URL']?>'+'/file/ct/'+response;
            a.download = response;
            document.body.append(a);
            a.click();
            a.remove();

        }
    });
});

// ------- busca dados produtos
$('body').on('click', '.produtos', function(e) {
    var idprod = $(this).attr("id");
    var spinner = document.querySelector('.spinner-border');
    spinner.style.display = 'block';
    $("#div_produtos").empty();
    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'busca_produtos', idprod: idprod},
        success: function (response) {
            var result = JSON.parse(response);
            if(response != 0){
                $("#div_produtos").append(result);
                spinner.style.display = 'none';
            }else{
                toastr["error"]("Ocorreu um erro. "+response);
            }
        }
    });
});

// ------- configuração de Laudo
$('body').on('click', '.config-laudo', function(e) {
    var idconfig = $(this).attr("id");
    $("#div_config_laudo").empty();
    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'configura_laudo', idconfig: idconfig},
        success: function (response) {
            var result = JSON.parse(response);
            if(response != 0){
                $("#div_config_laudo").append(result);

                if ($('#check1').is(":checked")) {
                    $("#div_laudo_anexo").css("display", "block");
                } else {
                    $("#div_laudo_anexo").css("display", "none");
                }
            }else{
                toastr["error"]("Ocorreu um erro. "+response);
            }
        }
    });
});

// aparecer e desaparecer o input de anexar laudo
$('body').on('change', '.radio_laudo', function(e) {
    if ($('#check1').is(":checked")) {
        $("#div_laudo_anexo").css("display", "block");
    } else {
        $("#div_laudo_anexo").css("display", "none");
        $('#div_anexolaudo').empty();
    }
});

// ADICIONAR ARQUIVO E CARREGAR TABELA - LAUDO
$('body').on('click', '#btn-anexo-laudo', function(e) {

    if($('#anexo_arquivo_laudo').val() != ""){
        // botão de adicionar arquivo
        const anexobutton = document.getElementById('btn-anexo-laudo');


        // verifica o tamanho do arquivo, se esta dentro do permitido. 
        var fileInput = document.getElementById('anexo_arquivo_laudo');
        var maxSize = 10000000;

        var fileSize = fileInput.files[0].size; // in bytes

        if(fileSize>maxSize){
            swal.fire("Atenção", "Tamanho do arquivo maior que 10 MegaBytes", "warning");
            return false;
        }else{

            // envia para o php para anexar
            var form_data = new FormData();

            form_data.append('action', 'anexar_laudo');
            form_data.append('id', $('#id_laudo_ct').val());
            form_data.append('anexonome',  $('#anexonome').val());
            form_data.append('radio',  $(".radio_laudo:checked").val());
            
            // ANEXAR ARQUIVOS
            var ins = document.getElementById('anexo_arquivo_laudo').files.length;
            for (var x = 0; x < ins; x++) {
                form_data.append("file", document.getElementById('anexo_arquivo_laudo').files[0]);
            }

            anexobutton.setAttribute('data-kt-indicator', 'on');
            anexobutton.disabled = true;

            $.post({
                url: "handle.php", // the resource where youre request will go throw
                type: "POST", // HTTP verb
                data: form_data,
                contentType: false,
                processData: false,
                success: function (response) {
                    if(response.trim() == '1'){
                        setTimeout(function() { 
                            toastr["success"]("Arquivo Adicionado.");
                        }, 1000);
                        $('#anexo_arquivo_laudo').val('');
                        $('#anexonome').val('');
                        
                        $.post({
                            url: "handle.php", // the resource where youre request will go throw
                            type: "POST", // HTTP verb
                            data: {action: "carrega_anexo_laudo", id: $('#id_laudo_ct').val()},
                            success: function (response) {
                                var result = JSON.parse(response);
                                $('#div_anexolaudo').empty();
                                $('#div_anexolaudo').append(result);
                                
                                if ($('#check1').is(":checked")) {
                                    $("#div_laudo_anexo").css("display", "block");
                                } else {
                                    $("#div_laudo_anexo").css("display", "none");
                                }
                            }
                        });
                    }
                    else if (response.trim() === "0") {
                        setTimeout(function() { 
                            toastr["error"]("Erro ao adicionar arquivo.");
                        }, 1000);
                    }
                    anexobutton.setAttribute('data-kt-indicator', 'off');
                    anexobutton.disabled = false;
                }
            });
        }
     }else{
        toastr["error"]("Selecione um arquivo.");
    }
});

// botão de salvar no modal de configuração do laudo
$('body').on('click', '.btn-salvar-config-laudo', function(e) {
    const valorSelecionado = $("input.radio_laudo:checked").val();
    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: "salvar_config_laudo", id: $('#id_laudo_ct').val(), radio: valorSelecionado},
        success: function (response) {
            if(response == 1){
                toastr["success"]("Salvo com sucesso.");
                setTimeout(function() { 
                    $("#modal_config").modal('toggle')
                }, 1000);
            }else{
                toastr["error"]("Ocorreu um erro. "+response);
            }
           
        }
    });
});

// apagar anexo 
$('body').on('click', '.btn-delete-laudo', function(e) {

    var id_delete = $(this).attr("id");
    var carta_delete = $(this).attr("data-id");

    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'apagar_arquivo_laudo', id: id_delete},
        success: function (response) {
            if(response == 1){
                toastr["success"]("Arquivo apagado com sucesso.");
                $.post({
                    url: "handle.php", // the resource where youre request will go throw
                    type: "POST", // HTTP verb
                    data: {action: "carrega_anexo_laudo", id: carta_delete},
                    success: function (response) {
                        var result = JSON.parse(response);
                        $('#div_anexolaudo').empty();
                        $('#div_anexolaudo').append(result);
                      
                    }
                });
            }else{
                toastr["error"]("Ocorreu um erro. "+response);
            }
        }
    });

});

// baixar laudo anexo // na tela de condiguração do laudo em anexo e tb para baixar após anexado pelo laboratório.
$('body').on('click', '.btn-download-laudo', function(e) {

    
    var id_download = $(this).attr("data-id");

    var a = document.createElement('a');
    a.href ='<?php echo  $_SESSION['URL'] ?>'+'/file/laudoAnexo/'+id_download.trim();
    a.download = id_download.trim();
    document.body.append(a);
    a.click();
    a.remove();

});

// ADICIONAR ARQUIVO E CARREGAR TABELA - LAUDO
$('body').on('click', '.carrega_baixar_laudo', function(e) {

        var carrega_laudo = $(this).attr("id");
        var spinner2 = document.querySelector('.spinner-border2');
        spinner2.style.display = 'block';

        $.post({
            url: "handle.php", // the resource where youre request will go throw
            type: "POST", // HTTP verb
            data: {action: 'carrega_baixar_laudo', id: carrega_laudo},
            success: function (response) {
                var result = JSON.parse(response);
                $('#div_modal_baixar_laudo').empty();
                $('#div_modal_baixar_laudo').append(result);
               
                spinner2.style.display = 'none';
            }
        });
  
});


// ADICIONAR ARQUIVO E CARREGAR TABELA - LAUDO
$('body').on('click', '.gerar_laudo_pdf', function(e) {
    const butao = document.querySelector('.gerar_laudo_pdf');
    var laudoid = $(this).attr("id");
    var laudosts = $(this).attr("data-id");

    butao.setAttribute('data-kt-indicator', 'on');
    butao.disabled = true;

    $.post({
        url: "handle.php", // the resource where youre request will go throw
        type: "POST", // HTTP verb
        data: {action: 'gerarlaudo', id: laudoid, status: laudosts},
        success: function (response) {
            var a = document.createElement('a');
            a.href = '<?php echo  $_SESSION['URL'] ?>'+'/file/laudoGerado/'+response;
            a.download = response;
            document.body.append(a);
            a.click();
            a.remove();
            butao.removeAttribute('data-kt-indicator');
            butao.disabled = false;
        }
    });

});




</script>
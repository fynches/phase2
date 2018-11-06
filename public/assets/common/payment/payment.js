$(document).ready(function () {

        oTable = $('#payment_index').DataTable({
            processing: true,
            serverSide: true,
            autoWidth: false,
            bRetrieve: true,
            "aaSorting": [[1, 'desc']],
            sPaginationType: "full_numbers",
            ajax: window.base_url + '/admin/payment/getData',
            sDom: "<'row'<'col-lg-6'l><'col-lg-6'f>r>t<'row'<'col-sm-6'i><'col-sm-6'p>>",
            columns: [
                {data: 'id', name: 'id'},
                {data: 'id', name: 'id'},
                {data: 'title', name: 'event.title'},
                {data: 'exp_name', name: 'experience.exp_name'},
                {data: 'first_name', name: 'users.first_name'},
                {data: 'bonus_amount', name: 'bonus_amount'},
                {data: 'make_annoymas', name: 'make_annoymas'},
                {data: 'id', name: 'id'}
            ],
            aoColumnDefs: [
                {
                    bSortable: false,
                    aTargets: [0]
                },
                {
                    bSortable: false,
                    aTargets: [7]
                },
            ],
            fnCreatedRow: function (nRow, aData, iDataIndex) {
                $('td:eq(0)', nRow).html('<input type="checkbox" class="checkboxes data_remove" name="checkboxes[]" value="' + aData.id + '"/>');
                $('td:eq(1)', nRow).css('text-align','center');
                $('td:eq(7)', nRow).html('<a href="'+ window.base_url + '/admin/payment/' + aData.id + '"><i class="fa fa-eye icon-muted fa-fw icon-space"></i></a>');
                
                if (aData.first_name != null)
                {
                    $('td:eq(4)', nRow).html(aData.first_name+' '+aData.last_name);
                } else {
                    $('td:eq(4)', nRow).html(aData.first_name);
                }
                
                if (aData.exp_name == null)
                {
                    $('td:eq(3)', nRow).html('Additional Amount');
                } else {
                    $('td:eq(3)', nRow).html(aData.exp_name);
                }
                 
                $('td:eq(4)', nRow).css('text-align','center');     
                $('td:eq(5)', nRow).css('text-align','center');        
                $('td:eq(6)', nRow).css('text-align','center');   
                $('td:eq(7)', nRow).css('text-align','center');                   
            },
            fnRowCallback: function (nRow, aData, iDisplayIndex) {
               $('td:eq(1)', nRow).html(iDisplayIndex + 1);
               nRow.setAttribute('id', "row_" + aData.id);
            }
        });
        //$("div.custtom_filter").html('<div class="dataTables_length custom_filter_header"><select id="filter-user-sort" class="input-sm form-control input-s-sm inline v-middle custom-filter-select"><option value="">Select</option><option value="1">Delete Multiple</option></select><button onclick="bulk_actions()" id="bulk_act" class="btn btn-sm btn-default apply-btn">Apply</button></div>');
        
        $(".group-checkable").change(function () {
            $(".data_remove").prop('checked', $(this).prop("checked"));
        });
    });
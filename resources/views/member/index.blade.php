@extends('layouts.master')

@section('title')
  Member
@endsection

@section('breadcrumb')   
@parent
<li class="active">Member</li> 
@endsection

{{-- content --}}

@section('content')

  
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          {{-- <h3 class="box-title">Monthly Recap Report</h3> --}}
          <button onclick="addForm(' {{ route('member.store') }}')" class="btn btn-success xs btn-flat">
              <i class="fa fa-plus-circle"> Data Member</i>
          </button>
        </div>
    
        <div class="box-body table-responsive">
            <table class="table table-stiped table-bordered">
                <thead>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Telepon</th>
                    <th>Alamat</th>
                    <th width="15%"><i class="fa fa-cog"></i></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  @includeIf('member.form')

@endsection

@push('scripts')
    <script>
        let table;

        $(function (){
            table =  $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('member.data')}}',

                },
                columns : [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'kode_member'},
                    {data: 'nama'},
                    {data: 'telepon'},
                    {data: 'alamat'},
                    {data: 'aksi', searchable: false, sortable: false }
                ]

            });

            $('#modal-form').validator().on('submit', function (e) {
                if (! e.preventDefault()) {
                    $.ajax({
                        url: $('#modal-form form').attr('action'),
                        type: 'post',
                        data: $('#modal-form form').serialize(),
                    })
                    .done((response) => {
                        $('#modal-form').modal('hide');
                        table.ajax.reload();
                    })
                    .fail((errors) => {
                        alert('tidak dapat menyimpan data');
                        return;
                    });
                } 
            })
        });

        // form
        function addForm(url)
        {
            // tampilkan form blade modal
            $('#modal-form').modal('show');
            // header modal
            $('#modal-form .modal-title').text('Tambah Member');

            // reset formnya
            $('#modal-form form')[0].reset();

// liat di button onclick saat submit 
            $('#modal-form form').attr('action', url);

            // untuk eksekusi edit  liat di form.blade modal
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama]').focus();


        }
        // action
        function editForm(url)
        {
            // tampilkan form blade modal
            $('#modal-form').modal('show');
            // header modal
            $('#modal-form .modal-title').text('Edit Member');

            // reset formnya
            $('#modal-form form')[0].reset();

            // liat di button onclick saat submit 
            $('#modal-form form').attr('action', url);

            // untuk eksekusi edit  liat di form.blade modal
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama]').focus();

            // ACTION
            $.get(url).done((response) => {
                $('#modal-form [name=nama]').val(response.nama);
                $('#modal-form [name=telepon]').val(response.telepon);
                $('#modal-form [name=alamat]').val(response.alamat
                );
            })
            .fail((errors) => {
                alert('data gagal diTamilkan')
                return;
            });


        }

        // delete data 
           // token di dapat dari meta yang di master
        function deleteData(url)
        {
           if (confirm('yakin ingin menghapus data ? ')) {
            $.post(url, {
                
                '_token': $('[name=csrf-token]').attr('content'),
                '_method': 'delete'
            })
            .done((response) => {
                table.ajax.reload();
            })
            .fail((errors) => {
                alert('data gagal dihpus');
                return;
            });
            }
        }
    </script>
@endpush
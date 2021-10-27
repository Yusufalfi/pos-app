@extends('layouts.master')

@section('title')
    Produk
@endsection

@section('breadcrumb')   
@parent
<li class="active">Tambah Produk</li> 
@endsection

{{-- content --}}

@section('content')

  
  <div class="row">
    <div class="col-md-12">
      <div class="box">
        <div class="box-header with-border">
          {{-- <h3 class="box-title">Monthly Recap Report</h3> --}}
          <button onclick="addForm(' {{ route('produk.store') }}')" class="btn btn-success xs btn-flat">
              <i class="fa fa-plus-circle"> Tambah data</i>
          </button>
        </div>
    
        <div class="box-body table-responsive">
            <table class="table table-stiped table-bordered">
                <thead>
                    <th width="5%">No</th>
                    <th>Kode</th>
                    <th>Nama</th>
                    <th>Kategori</th>
                    <th>Merk</th>
                    <th>Harga Beli</th>
                    <th>Harga Jual</th>
                    <th>Diskon</th>
                    <th>Stok</th>
                    <th width="15%"><i class="fa fa-cog"></i></th>
                </thead>
                <tbody></tbody>
            </table>
        </div>
      </div>
    </div>
  </div>

  @includeIf('produk.form')

@endsection

@push('scripts')
    <script>
        let table;

        $(function (){
            table =  $('.table').DataTable({
                processing: true,
                autoWidth: false,
                ajax: {
                    url: '{{ route('produk.data')}}',

                },
                columns : [
                    {data: 'DT_RowIndex', searchable: false, sortable: false},
                    {data: 'kode_produk'},
                    {data: 'nama_produk'},
                    {data: 'nama_kategori'},
                    {data: 'merk'},
                    {data: 'harga_beli'},
                    {data: 'harga_jual'},
                    {data: 'diskon'},
                    {data: 'stok'},
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
            $('#modal-form .modal-title').text('Tambah Produk');

            // reset formnya
            $('#modal-form form')[0].reset();

// liat di button onclick saat submit 
            $('#modal-form form').attr('action', url);

            // untuk eksekusi edit  liat di form.blade modal
            $('#modal-form [name=_method]').val('post');
            $('#modal-form [name=nama_produk]').focus();


        }
        // action
        function editForm(url)
        {
            // tampilkan form blade modal
            $('#modal-form').modal('show');
            // header modal
            $('#modal-form .modal-title').text('Edit Produk');

            // reset formnya
            $('#modal-form form')[0].reset();

            // liat di button onclick saat submit 
            $('#modal-form form').attr('action', url);

            // untuk eksekusi edit  liat di form.blade modal
            $('#modal-form [name=_method]').val('put');
            $('#modal-form [name=nama_produk]').focus();

            // ACTION
            $.get(url).done((response) => {
                $('#modal-form [name=nama_produk]').val(response.nama_produk);
                // $('#modal-form [name=kode_produk]').val(response.kode_produk);
                $('#modal-form [name=id_kategori]').val(response.id_kategori);
                $('#modal-form [name=merk]').val(response.merk);
                $('#modal-form [name=harga_beli]').val(response.harga_beli);
                $('#modal-form [name=harga_jual]').val(response.harga_jual);
                $('#modal-form [name=diskon]').val(response.diskon);
                $('#modal-form [name=stok]').val(response.stok);
            })
            .fail((errors) => {
                alert('data gagal diUbah')
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
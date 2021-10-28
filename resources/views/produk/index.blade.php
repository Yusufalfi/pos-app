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
          <div class="btn-group">

              <button onclick="addForm(' {{ route('produk.store') }}')" class="btn btn-success xs btn-flat">
                  <i class="fa fa-plus-circle"> Tambah data</i>
              </button>

                {{--button delete select --}}
              <button onclick="deleteSelected(' {{route('produk.delete_selected')}}')" class="btn btn-danger xs btn-flat" style="margin-left: 5px">
                  <i class="fa fa-trash"> Hapus </i>
              </button>

                {{--cetak barkot --}}
              <button onclick="cetakBarcode(' {{route('produk.cetak_barcode')}}')" class="btn btn-info xs btn-flat" style="margin-left: 5px">
                  <i class="fa fa-barcode"> Cetak Barcode </i>
              </button>

          </div>
        </div>
    
        <div class="box-body table-responsive">
            <form action="" class="form-produk" method="POST">
                @csrf
                <table class="table table-stiped table-bordered">
                    <thead>
                        <th>
                            <input type="checkbox" name="select_all" id="select_all">
                        </th>
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
           </form>
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
                    {data: 'select_all'},
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

            // multiple select
            $('[name=select_all]').on('click', function() {
                    // cari semua yg typenya checkbox 
                $(':checkbox').prop('checked', this.checked);
            });




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

        // function delete multiSelected
        function deleteSelected(url)
        {
            if($('input:checked').length > 1) {
                if(confirm('yakin ingin menghapus data yang terpilih')) {
                    $.post(url, $('.form-produk').serialize())
                    // jika berhasil reload table
                    .done((response) => {
                        table.ajax.reload();
                    })
                    .fails((errors) => {
                        alert('tidak dapat menghapus data');
                        return;
                    });
                }
            } else {
                alert('pilih data yang ingin di hapus');
                return;
            }
            // console.log('ok');
        }

        // cetak barcode
        function cetakBarcode(url)
        {
            if($('input:checked').length < 1) {
                alert('pilih data yg ingin di cetak');
            } else if($('input:checked').length < 3){
                alert('pilih data minimal 3 yang di ingin cetak');
                return;
            } else {
                $('.form-produk').attr('action', url).attr('target', '_blank').submit();
                // return;
            }
        }

    </script>
@endpush
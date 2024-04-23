@extends('layouts.main', ['title' => 'Laporan'])
@section('title-content')
    <i class="fas fa-print mr-2"></i> Laporan
@endsection
@section('content')
    <div class="row">
        <div class="col-lg-6 col-xl-4">
            <form action="{{ route('laporan.harian') }}" class="card card-orange card-outline" target="_blank">
                <div class="card-header">
                    <h3 class="card-title">Buat Laporan Harian</h3>
                </div>
                <div class="card-body">
                    <div class="form-group">
                        <label for="">Tanggal</label>
                        <input type="date" name="tanggal" class="form-control" value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="form-group">
                        <label for="">Petugas</label>
                        <x-select name="user_id" :options="$officerList" />
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
        <div class="col-lg-6 col-xl-4">
            <form action="{{ route('laporan.bulanan') }}" target="_blank" class="card card-orange card-outline">
                <div class="card-header">
                    <h3 class="card-title">Buat Laporan Bulanan</h3>
                </div>
                <div class="card-body">
                    <div class="form-group row">
                        <div class="col">
                            <label for="">Bulan</label>
                            @php
                                $dataBulan = [
                                    'Pilih Bulan : ',
                                    'Januari',
                                    'Februari',
                                    'Maret',
                                    'April',
                                    'Mei',
                                    'Juni',
                                    'Juli',
                                    'Agustus',
                                    'September',
                                    'Oktober',
                                    'November',
                                    'Desember',
                                ];
                            @endphp
                            <select name="bulan" class="form-control @error('bulan') is-invalid @enderror">
                                @foreach ($dataBulan as $key => $bulan)
                                    <option value="{{ $key ? $key : '' }}" >{{ $bulan }}</option>
                                @endforeach
                            </select>
                            @error('bulan')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                        <div class="col">
                            <label for="">Tahun</label>
                            @php
                                $tahun = date('Y');
                                $max = $tahun - 5;
                            @endphp
                            <select name="tahun" class="form-control @error('tahun') is-invalid @enderror">
                                <option value="">Pilih Tahun :</option>
                                @for ($tahun; $tahun > $max; $tahun--)
                                    <option value="{{ $tahun }}">{{ $tahun }}</option>
                                @endfor
                            </select>
                            @error('tahun')
                                <div class="invalid-feedback">
                                    {{ $message }}
                                </div>
                            @enderror
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="">Petugas</label>
                        <x-select name="user_id" :options="$officerList" />
                    </div>
                </div>
                <div class="card-footer">
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-print mr-2"></i> Cetak
                    </button>
                </div>
            </form>
        </div>
    </div>
@endsection

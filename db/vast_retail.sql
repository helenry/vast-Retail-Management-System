DROP DATABASE IF EXISTS vast_retail;
CREATE DATABASE IF NOT EXISTS vast_retail;

USE vast_retail;

CREATE TABLE branch (
    Id INT NOT NULL AUTO_INCREMENT,
	EmailManager VARCHAR(60) NOT NULL,
    PRIMARY KEY (Id)
);

CREATE TABLE suppliers (
	Id INT NOT NULL AUTO_INCREMENT,
	Nama TEXT NOT NULL,
	Email VARCHAR(60) NOT NULL,
	PRIMARY KEY (Id)
);

CREATE TABLE employee (
	Email VARCHAR(60) NOT NULL,
	Password TEXT NOT NULL,
    Nama TEXT NOT NULL,
    Jabatan TEXT NOT NULL,
    IdCabang INT NULL,
	PRIMARY KEY (Email)
);

CREATE TABLE address (
    Jenis VARCHAR(8) NOT NULL, -- cabang atau supplier
    IdToko INT NOT NULL,
    Provinsi TEXT NOT NULL,
    Kabupaten TEXT NOT NULL,
    Kecamatan TEXT NOT NULL,
    Kelurahan TEXT NOT NULL,
	Alamat TEXT NOT NULL,
	KodePos INT NOT NULL,
	PRIMARY KEY(Jenis, IdToko)
);

CREATE TABLE produk (
	NamaProduk TEXT NOT NULL,
    KodeProduk INT NOT NULL AUTO_INCREMENT,
    HargaBeli INT(10) NOT NULL,
    HargaJual INT(10) NOT NULL,
	Kategori INT NOT NULL,
	IdSupplier INT NOT NULL,
	PRIMARY KEY (KodeProduk)
);

CREATE TABLE inventory (
    KodeProduk INT NOT NULL,
    NamaProduk VARCHAR(60) NOT NULL,
    Stok INT(10) NOT NULL,
    Kategori TEXT NOT NULL,
    HargaBeli INT(10) NOT NULL,
    HargaJual INT(10) NOT NULL,
    IdCabang INT NOT NULL,
    PRIMARY KEY (KodeProduk, IdCabang)
);

CREATE TABLE pemesanan ( -- tidak perlu dihapus
	NoTransaksi VARCHAR(15) NOT NULL,
	WaktuDibuat TIMESTAMP NOT NULL,
	WaktuDibayar TIMESTAMP NULL,
	WaktuDikirim TIMESTAMP NULL,
	WaktuDiterima TIMESTAMP NULL,
	Total BIGINT NOT NULL,
	Status TEXT NOT NULL,
	IdCabang INT NOT NULL,
    EmailManagerCabang VARCHAR(60) NOT NULL,
    KelurahanCabang TEXT NOT NULL,
    ProvinsiCabang TEXT NOT NULL,
    KabupatenCabang TEXT NOT NULL,
    KecamatanCabang TEXT NOT NULL,
    AlamatCabang TEXT NOT NULL,
    KodePosCabang INT NOT NULL,
	IdSupplier INT NOT NULL,
    NamaSupplier TEXT NOT NULL,
	EmailSupplier VARCHAR(60) NOT NULL,
    ProvinsiSupplier TEXT NOT NULL,
    KabupatenSupplier TEXT NOT NULL,
    KecamatanSupplier TEXT NOT NULL,
    KelurahanSupplier TEXT NOT NULL,
    AlamatSupplier TEXT NOT NULL,
    KodePosSupplier INT NOT NULL,
	PRIMARY KEY (NoTransaksi)
);

CREATE TABLE produk_transaksi ( -- tidak perlu dihapus
	NoTransaksi VARCHAR(15) NOT NULL,
    KodeProduk INT NOT NULL,
    NamaProduk VARCHAR(60) NOT NULL,
    Kategori TEXT NOT NULL,
    HargaBeli INT(10) NOT NULL,
    HargaJual INT(10) NOT NULL,
	Kuantitas INT(4) NOT NULL,
	Subtotal BIGINT NOT NULL,
    PRIMARY KEY(NoTransaksi)
);
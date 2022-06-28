DROP DATABASE IF EXISTS vast_retail;
CREATE DATABASE IF NOT EXISTS vast_retail;

USE vast_retail;

CREATE TABLE branch (
    Id INT NOT NULL AUTO_INCREMENT,
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
    IdCabang INT NOT NULL,
	PRIMARY KEY (Email)
	-- FOREIGN KEY (IdCabang) REFERENCES branch(Id)
);

CREATE TABLE address (
    Jenis TEXT NOT NULL, -- cabang atau supplier
    IdToko INT NOT NULL,
    Provinsi TEXT NOT NULL,
    Kabupaten TEXT NOT NULL,
    Kecamatan TEXT NOT NULL,
    Kelurahan TEXT NOT NULL
	-- FOREIGN KEY (IdToko) REFERENCES branch(Id),
	-- FOREIGN KEY (IdToko) REFERENCES supplier(Id)
);

CREATE TABLE produk (
	NamaProduk VARCHAR(60) NOT NULL,
    KodeProduk INT NOT NULL AUTO_INCREMENT,
    HargaBeli INT(10) NOT NULL,
    HargaJual INT(10) NOT NULL,
	Kategori TEXT NOT NULL,
	IdSupplier INT NOT NULL,
	PRIMARY KEY (KodeProduk)
);

CREATE TABLE inventory (
    KodeProduk INT NOT NULL,
    Stok INT(10) NOT NULL,
    Expired DATE NOT NULL,
    IdCabang INT NOT NULL
	-- FOREIGN KEY (KodeProduk) REFERENCES produk(KodeProduk),
	-- FOREIGN KEY (IdCabang) REFERENCES branch(Id)
);

CREATE TABLE penjualan (
	NoTransaksi INT NOT NULL AUTO_INCREMENT,
	IdCabang INT NOT NULL,
	Total INT(10) NOT NULL,
	PRIMARY KEY (NoTransaksi)
);

CREATE TABLE pemesanan (
	NoTransaksi INT NOT NULL AUTO_INCREMENT,
	EmailKaryawan VARCHAR(60) NOT NULL,
	Total INT(10) NOT NULL,
	Status TEXT NOT NULL,
	IdCabang INT NOT NULL,
	IdSupplier INT NOT NULL,
	PRIMARY KEY (NoTransaksi)
	-- FOREIGN KEY (IdCabang) REFERENCES branch(Id)
	-- FOREIGN KEY (IdSupplier) REFERENCES supplier(Id)
);

CREATE TABLE produk_transaksi (
	WaktuTransaksi TIMESTAMP NOT NULL,
	JenisTransaksi TEXT NOT NULL, -- penjualan atau pemesanan
	NoTransaksi INT NOT NULL,
    KodeProduk INT NOT NULL,
	Kuantitas INT(4) NOT NULL,
	SubTotal INT(9) NOT NULL
	-- FOREIGN KEY (NoTransaksi) REFERENCES penjualan(NoTransaksi),
	-- FOREIGN KEY (NoTransaksi) REFERENCES pemesanan(NoTransaksi),
	-- FOREIGN KEY (KodeProduk) REFERENCES produk(KodeProduk)
);
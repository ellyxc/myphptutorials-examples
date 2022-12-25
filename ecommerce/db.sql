CREATE TABLE IF NOT EXISTS public.users
(
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1 ),
    nama character varying(255) COLLATE pg_catalog."default",
    email character varying(255) COLLATE pg_catalog."default" NOT NULL,
    password character varying(255) COLLATE pg_catalog."default",
    tipe character varying(20) COLLATE pg_catalog."default" NOT NULL,
    CONSTRAINT users_pkey PRIMARY KEY (id),
    CONSTRAINT users_email_key UNIQUE (email)
)

CREATE TABLE IF NOT EXISTS public.alamat
(
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1 ),
    id_user bigint NOT NULL,
    jalan character varying(255) COLLATE pg_catalog."default",
    desa character varying(255) COLLATE pg_catalog."default",
    kecamatan character varying(255) COLLATE pg_catalog."default",
    kabupaten character varying(255) COLLATE pg_catalog."default",
    provinsi character varying(255) COLLATE pg_catalog."default",
    kodepos character varying(20) COLLATE pg_catalog."default",
    CONSTRAINT alamat_pkey PRIMARY KEY (id),
    CONSTRAINT alamat_id_user_fkey FOREIGN KEY (id_user)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

CREATE TABLE IF NOT EXISTS public.pesanan
(
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1 ),
    id_user bigint NOT NULL,
    total_harga bigint,
    tanggal_pesanan date,
    id_alamat bigint,
    CONSTRAINT pesanan_pkey PRIMARY KEY (id),
    CONSTRAINT pesanan_id_alamat_fkey FOREIGN KEY (id_alamat)
        REFERENCES public.alamat (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
        NOT VALID,
    CONSTRAINT pesanan_id_user_fkey FOREIGN KEY (id_user)
        REFERENCES public.users (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)

CREATE TABLE IF NOT EXISTS public.item_pesanan
(
    id bigint NOT NULL GENERATED ALWAYS AS IDENTITY ( INCREMENT 1 START 1 MINVALUE 1 MAXVALUE 9223372036854775807 CACHE 1 ),
    id_pesanan bigint NOT NULL,
    id_produk bigint,
    harga integer,
    qty integer,
    CONSTRAINT item_pesanan_pkey PRIMARY KEY (id),
    CONSTRAINT item_pesanan_id_pesanan_fkey FOREIGN KEY (id_pesanan)
        REFERENCES public.pesanan (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION,
    CONSTRAINT item_pesanan_id_produk_fkey FOREIGN KEY (id_produk)
        REFERENCES public.produk (id) MATCH SIMPLE
        ON UPDATE NO ACTION
        ON DELETE NO ACTION
)


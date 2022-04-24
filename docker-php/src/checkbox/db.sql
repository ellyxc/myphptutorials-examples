CREATE TABLE `siswa` (
  `id` int NOT NULL,
  `nis` varchar(10) DEFAULT NULL,
  `nama` varchar(255) NOT NULL,
  `jenis_kelamin` varchar(10) NOT NULL,
  `tgl_lahir` date NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_0900_ai_ci;

ALTER TABLE `siswa`
  ADD PRIMARY KEY (`id`);

ALTER TABLE `siswa`
  MODIFY `id` int NOT NULL AUTO_INCREMENT;
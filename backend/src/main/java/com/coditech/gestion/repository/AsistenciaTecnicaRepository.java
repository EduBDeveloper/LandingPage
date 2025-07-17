package com.coditech.gestion.repository;

import com.coditech.gestion.entities.AsistenciaTecnica;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import org.springframework.data.jpa.repository.JpaRepository;
import org.springframework.stereotype.Repository;

import java.time.LocalDate;

@Repository
public interface AsistenciaTecnicaRepository extends JpaRepository<AsistenciaTecnica, Long> {

    long countByFechaAsistencia(LocalDate fechaAsistencia);

    // Filtro por empresa y fecha
    Page<AsistenciaTecnica> findByEmpresaIdAndFechaAsistencia(Long empresaId, LocalDate fecha, Pageable pageable);

    // Filtro solo por empresa
    Page<AsistenciaTecnica> findByEmpresaId(Long empresaId, Pageable pageable);

    // Filtro solo por fecha
    Page<AsistenciaTecnica> findByFechaAsistencia(LocalDate fecha, Pageable pageable);

}

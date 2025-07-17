package com.coditech.gestion.service;

import org.springframework.data.domain.Page;
import org.springframework.data.domain.Pageable;
import com.coditech.gestion.dto.DashboardStats;
import com.coditech.gestion.entities.AsistenciaTecnica;
import com.coditech.gestion.repository.AsistenciaTecnicaRepository;
import com.coditech.gestion.repository.ClienteRepository;
import com.coditech.gestion.repository.EmpresaRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.time.LocalDate;
import java.util.List;

@Service
public class AsistenciaTecnicaService {

    @Autowired
    private AsistenciaTecnicaRepository repo;
    @Autowired
    private AsistenciaTecnicaRepository asistenciaRepository;

    @Autowired
    private ClienteRepository clienteRepository;

    @Autowired
    private EmpresaRepository empresaRepository;

    public AsistenciaTecnica guardar(AsistenciaTecnica a) {
        return repo.save(a);
    }

    public List<AsistenciaTecnica> listar() {
        return repo.findAll();
    }

    public DashboardStats obtenerEstadisticasDashboard() {
        long asistenciasHoy = asistenciaRepository.countByFechaAsistencia(LocalDate.now());
        long tecnicosActivos = clienteRepository.count();
        long totalEmpresas = empresaRepository.count();

        return new DashboardStats(asistenciasHoy, tecnicosActivos, totalEmpresas);
    }

    public Page<AsistenciaTecnica> listarPaginado(Long empresaId, LocalDate fecha, Pageable pageable) {
        if (empresaId != null && fecha != null) {
            return repo.findByEmpresaIdAndFechaAsistencia(empresaId, fecha, pageable);
        } else if (empresaId != null) {
            return repo.findByEmpresaId(empresaId, pageable);
        } else if (fecha != null) {
            return repo.findByFechaAsistencia(fecha, pageable);
        } else {
            return repo.findAll(pageable);
        }
    }
}

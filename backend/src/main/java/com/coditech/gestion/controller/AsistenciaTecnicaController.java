package com.coditech.gestion.controller;

import com.coditech.gestion.dto.DashboardStats;
import com.coditech.gestion.entities.AsistenciaTecnica;
import com.coditech.gestion.service.AsistenciaTecnicaService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.data.domain.Page;
import org.springframework.data.domain.PageRequest;
import org.springframework.data.domain.Sort;
import org.springframework.web.bind.annotation.*;

import java.time.LocalDate;
import java.util.List;

@RestController
@RequestMapping("/api/asistencias")
public class AsistenciaTecnicaController {

    @Autowired
    private AsistenciaTecnicaService service;

    @PostMapping
    public AsistenciaTecnica crear(@RequestBody AsistenciaTecnica asistencia) {
        return service.guardar(asistencia);
    }

    @GetMapping
    public List<AsistenciaTecnica> listar() {
        return service.listar();
    }

    @GetMapping("/dashboard")
    public DashboardStats getDashboardStats() {
        return service.obtenerEstadisticasDashboard();
    }

    @GetMapping("/paginado")
    public Page<AsistenciaTecnica> listarPaginado(
            @RequestParam(required = false) Long empresaId,
            @RequestParam(required = false) String fecha,
            @RequestParam(defaultValue = "0") int page,
            @RequestParam(defaultValue = "5") int size
    ) {
        LocalDate fechaAsistencia = (fecha != null && !fecha.isEmpty()) ? LocalDate.parse(fecha) : null;
        return service.listarPaginado(empresaId, fechaAsistencia, PageRequest.of(page, size, Sort.by("fechaAsistencia").descending()));
    }
}

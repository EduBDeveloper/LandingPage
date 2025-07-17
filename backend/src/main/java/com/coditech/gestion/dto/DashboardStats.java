package com.coditech.gestion.dto;

import lombok.AllArgsConstructor;
import lombok.Data;

@Data
@AllArgsConstructor
public class DashboardStats {
    private long asistenciasHoy;
    private long tecnicosActivos;
    private long totalEmpresas;
}

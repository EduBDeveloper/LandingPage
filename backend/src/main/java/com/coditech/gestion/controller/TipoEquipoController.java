package com.coditech.gestion.controller;

import com.coditech.gestion.entities.TipoEquipo;
import com.coditech.gestion.service.TipoEquipoService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/tipoequipos")
public class TipoEquipoController {

    @Autowired
    private TipoEquipoService service;

    @PostMapping
    public TipoEquipo crear(@RequestBody TipoEquipo tipo) {
        return service.guardar(tipo);
    }

    @GetMapping
    public List<TipoEquipo> listar() {
        return service.listar();
    }
}

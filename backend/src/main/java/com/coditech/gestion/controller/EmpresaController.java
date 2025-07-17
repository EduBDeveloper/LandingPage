package com.coditech.gestion.controller;

import com.coditech.gestion.entities.Empresa;
import com.coditech.gestion.service.EmpresaService;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.http.ResponseEntity;
import org.springframework.web.bind.annotation.*;

import java.util.List;

@RestController
@RequestMapping("/api/empresas")
public class EmpresaController {

    @Autowired private EmpresaService service;

    @PostMapping
    public Empresa crear(@RequestBody Empresa empresa) {
        return service.guardar(empresa);
    }

    @GetMapping
    public List<Empresa> listar() {
        return service.listar();
    }

    @PutMapping("/{id}")
    public ResponseEntity<Empresa> actualizar(@PathVariable Long id, @RequestBody Empresa empresa) {
        Empresa actualizada = service.actualizar(id, empresa);
        if (actualizada != null) {
            return ResponseEntity.ok(actualizada);
        } else {
            return ResponseEntity.notFound().build();
        }
    }

    @DeleteMapping("/{id}")
    public ResponseEntity<Void> eliminar(@PathVariable Long id) {
        if (service.eliminar(id)) {
            return ResponseEntity.noContent().build();
        } else {
            return ResponseEntity.notFound().build();
        }
    }
}

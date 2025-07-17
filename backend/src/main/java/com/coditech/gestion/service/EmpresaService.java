package com.coditech.gestion.service;

import com.coditech.gestion.entities.Empresa;
import com.coditech.gestion.repository.EmpresaRepository;
import org.springframework.beans.factory.annotation.Autowired;
import org.springframework.stereotype.Service;

import java.util.List;
import java.util.Optional;

@Service
public class EmpresaService {

    @Autowired private EmpresaRepository repo;

    public Empresa guardar(Empresa e) {
        return repo.save(e);
    }

    public List<Empresa> listar() {
        return repo.findAll();
    }

    public Empresa actualizar(Long id, Empresa empresa) {
        Optional<Empresa> existente = repo.findById(id);
        if (existente.isPresent()) {
            Empresa e = existente.get();
            e.setNombre(empresa.getNombre());
            return repo.save(e);
        }
        return null;
    }

    public boolean eliminar(Long id) {
        if (repo.existsById(id)) {
            repo.deleteById(id);
            return true;
        }
        return false;
    }
}

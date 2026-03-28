<?php
// Database Installation Script for MCA
// Supports both PostgreSQL (Replit) and MySQL (Hostinger)

require_once __DIR__ . '/database.php';

$isPostgres = DB_TYPE === 'pgsql';

try {
    $pdo = getConnection();
    
    echo "Installing database for MCA...\n";
    echo "Database type: " . DB_TYPE . "\n\n";
    
    if ($isPostgres) {
        // PostgreSQL schema
        
        // Drop tables if exist
        $pdo->exec("DROP TABLE IF EXISTS actividad_log CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS respuestas_formularios CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS formularios CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS intercesion CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS publicaciones CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS estadisticas_mensuales CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS estadisticas_mensuales_supervisores CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS estadisticas_mensuales_admin CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS asistencia_semanal CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS asistencia_supervisores CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS asistencia_admin CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS informes_semanales CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS informes_supervisores CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS informes_admin CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS miembros CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS supervisor_lideres CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS configuracion CASCADE");
        $pdo->exec("DROP TABLE IF EXISTS users CASCADE");
        
        // Create users table
        $pdo->exec("CREATE TABLE users (
            id SERIAL PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            role VARCHAR(30) DEFAULT 'user' CHECK (role IN ('admin', 'supervisor', 'lider', 'admin_jovenes', 'admin_mujeres', 'admin_varones', 'admin_juveniles', 'admin_evangelismo', 'admin_intercesion', 'user')),
            is_active BOOLEAN DEFAULT TRUE,
            last_login TIMESTAMP,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Supervisor-Leader assignments
        $pdo->exec("CREATE TABLE supervisor_lideres (
            id SERIAL PRIMARY KEY,
            supervisor_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            lider_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (supervisor_id, lider_id)
        )");
        
        // Members
        $pdo->exec("CREATE TABLE miembros (
            id SERIAL PRIMARY KEY,
            lider_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            nombre VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            email VARCHAR(100),
            es_consolidacion BOOLEAN DEFAULT FALSE,
            fecha_bautizo DATE,
            esta_bautizado BOOLEAN DEFAULT FALSE,
            nota_consolidacion INT DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Weekly reports
        $pdo->exec("CREATE TABLE informes_semanales (
            id SERIAL PRIMARY KEY,
            lider_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            semana_inicio DATE NOT NULL,
            semana_fin DATE NOT NULL,
            anfitrion VARCHAR(100),
            fecha_reunion DATE,
            direccion VARCHAR(255),
            hora_inicio TIME,
            hora_termino TIME,
            total_miembros INT DEFAULT 0,
            en_consolidacion INT DEFAULT 0,
            total_asistentes INT DEFAULT 0,
            nuevos_evangelizados INT DEFAULT 0,
            confesiones_fe INT DEFAULT 0,
            tema_expuesto VARCHAR(255),
            observaciones TEXT,
            ofrenda DECIMAL(10,2) DEFAULT 0,
            proxima_fecha_omt DATE,
            estado VARCHAR(20) DEFAULT 'borrador' CHECK (estado IN ('borrador', 'completado')),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (lider_id, semana_inicio)
        )");
        
        // Weekly attendance
        $pdo->exec("CREATE TABLE asistencia_semanal (
            id SERIAL PRIMARY KEY,
            informe_id INT NOT NULL REFERENCES informes_semanales(id) ON DELETE CASCADE,
            miembro_id INT NOT NULL REFERENCES miembros(id) ON DELETE CASCADE,
            grupo_familiar VARCHAR(10) DEFAULT 'no' CHECK (grupo_familiar IN ('si', 'no', 'no_hubo')),
            escuela VARCHAR(10) DEFAULT 'no' CHECK (escuela IN ('si', 'no', 'no_hubo')),
            reunion_red VARCHAR(10) DEFAULT 'no' CHECK (reunion_red IN ('si', 'no', 'no_hubo')),
            culto_domingo VARCHAR(10) DEFAULT 'no' CHECK (culto_domingo IN ('si', 'no', 'no_hubo')),
            actividad_omt VARCHAR(10) DEFAULT 'no' CHECK (actividad_omt IN ('si', 'no', 'no_hubo')),
            porcentaje_asistencia DECIMAL(5,2) DEFAULT 0,
            fecha_llamada_visita DATE,
            nota_consolidacion INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Monthly statistics
        $pdo->exec("CREATE TABLE estadisticas_mensuales (
            id SERIAL PRIMARY KEY,
            lider_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            mes INT NOT NULL,
            anio INT NOT NULL,
            promedio_grupo_familiar DECIMAL(5,2) DEFAULT 0,
            promedio_escuela DECIMAL(5,2) DEFAULT 0,
            promedio_reunion_red DECIMAL(5,2) DEFAULT 0,
            promedio_culto_domingo DECIMAL(5,2) DEFAULT 0,
            promedio_actividad_omt DECIMAL(5,2) DEFAULT 0,
            promedio_general DECIMAL(5,2) DEFAULT 0,
            total_miembros INT DEFAULT 0,
            total_bautizados INT DEFAULT 0,
            total_consolidacion INT DEFAULT 0,
            archivo_pdf VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            UNIQUE (lider_id, mes, anio)
        )");
        
        // supervisor reports and attendance
        $pdo->exec("CREATE TABLE informes_supervisores (
            id SERIAL PRIMARY KEY,
            supervisor_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            semana_inicio DATE NOT NULL,
            semana_fin DATE NOT NULL,
            anfitrion VARCHAR(255),
            fecha_reunion DATE,
            direccion VARCHAR(255),
            hora_inicio TIME,
            hora_termino TIME,
            total_miembros INT DEFAULT 0,
            total_asistentes INT DEFAULT 0,
            nuevos_evangelizados INT DEFAULT 0,
            confesiones_fe INT DEFAULT 0,
            tema_expuesto TEXT,
            observaciones TEXT,
            ofrenda DECIMAL(10,2) DEFAULT 0,
            proxima_fecha_omt DATE,
            estado VARCHAR(20) DEFAULT 'borrador',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (supervisor_id, semana_inicio)
        )");
        
        $pdo->exec("CREATE TABLE asistencia_supervisores (
            id SERIAL PRIMARY KEY,
            informe_id INT NOT NULL REFERENCES informes_supervisores(id) ON DELETE CASCADE,
            lider_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            grupo_familiar VARCHAR(10) DEFAULT 'no',
            escuela VARCHAR(10) DEFAULT 'no',
            reunion_red VARCHAR(10) DEFAULT 'no',
            culto_domingo VARCHAR(10) DEFAULT 'no',
            actividad_omt VARCHAR(10) DEFAULT 'no',
            porcentaje_asistencia DECIMAL(5,2) DEFAULT 0,
            fecha_llamada_visita DATE,
            nota_consolidacion INT DEFAULT 0
        )");
        
        $pdo->exec("CREATE TABLE estadisticas_mensuales_supervisores (
            id SERIAL PRIMARY KEY,
            supervisor_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            mes INT NOT NULL,
            anio INT NOT NULL,
            promedio_grupo_familiar DECIMAL(5,2) DEFAULT 0,
            promedio_escuela DECIMAL(5,2) DEFAULT 0,
            promedio_reunion_red DECIMAL(5,2) DEFAULT 0,
            promedio_culto_domingo DECIMAL(5,2) DEFAULT 0,
            promedio_actividad_omt DECIMAL(5,2) DEFAULT 0,
            promedio_general DECIMAL(5,2) DEFAULT 0,
            total_miembros INT DEFAULT 0,
            UNIQUE (supervisor_id, mes, anio)
        )");
        
        // admin reports and stats
        $pdo->exec("CREATE TABLE informes_admin (
            id SERIAL PRIMARY KEY,
            admin_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            semana_inicio DATE NOT NULL,
            semana_fin DATE NOT NULL,
            anfitrion VARCHAR(255),
            fecha_reunion DATE,
            direccion VARCHAR(255),
            hora_inicio TIME,
            hora_termino TIME,
            total_miembros INT DEFAULT 0,
            total_asistentes INT DEFAULT 0,
            nuevos_evangelizados INT DEFAULT 0,
            confesiones_fe INT DEFAULT 0,
            tema_expuesto TEXT,
            observaciones TEXT,
            ofrenda DECIMAL(10,2) DEFAULT 0,
            proxima_fecha_omt DATE,
            estado VARCHAR(20) DEFAULT 'borrador',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            INDEX (admin_id, semana_inicio)
        )");
        
        $pdo->exec("CREATE TABLE asistencia_admin (
            id SERIAL PRIMARY KEY,
            informe_id INT NOT NULL REFERENCES informes_admin(id) ON DELETE CASCADE,
            supervisor_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            grupo_familiar VARCHAR(10) DEFAULT 'no',
            escuela VARCHAR(10) DEFAULT 'no',
            reunion_red VARCHAR(10) DEFAULT 'no',
            culto_domingo VARCHAR(10) DEFAULT 'no',
            actividad_omt VARCHAR(10) DEFAULT 'no',
            porcentaje_asistencia DECIMAL(5,2) DEFAULT 0,
            fecha_llamada_visita DATE,
            nota_consolidacion INT DEFAULT 0
        )");
        
        $pdo->exec("CREATE TABLE estadisticas_mensuales_admin (
            id SERIAL PRIMARY KEY,
            admin_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            mes INT NOT NULL,
            anio INT NOT NULL,
            promedio_grupo_familiar DECIMAL(5,2) DEFAULT 0,
            promedio_escuela DECIMAL(5,2) DEFAULT 0,
            promedio_reunion_red DECIMAL(5,2) DEFAULT 0,
            promedio_culto_domingo DECIMAL(5,2) DEFAULT 0,
            promedio_actividad_omt DECIMAL(5,2) DEFAULT 0,
            promedio_general DECIMAL(5,2) DEFAULT 0,
            total_miembros INT DEFAULT 0,
            UNIQUE (admin_id, mes, anio)
        )");
        
        // Publications
        $pdo->exec("CREATE TABLE publicaciones (
            id SERIAL PRIMARY KEY,
            seccion VARCHAR(20) NOT NULL CHECK (seccion IN ('jovenes', 'mujeres', 'varones', 'juveniles', 'evangelismo', 'intercesion')),
            autor_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            titulo VARCHAR(255) NOT NULL,
            slug VARCHAR(255),
            contenido TEXT NOT NULL,
            imagen VARCHAR(255),
            video_url VARCHAR(255),
            es_destacado BOOLEAN DEFAULT FALSE,
            estado VARCHAR(20) DEFAULT 'borrador' CHECK (estado IN ('borrador', 'publicado')),
            vistas INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Prayer requests
        $pdo->exec("CREATE TABLE intercesion (
            id SERIAL PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            email VARCHAR(100),
            peticion TEXT NOT NULL,
            es_privada BOOLEAN DEFAULT FALSE,
            estado VARCHAR(20) DEFAULT 'pendiente' CHECK (estado IN ('pendiente', 'aprobada', 'rechazada')),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Forms
        $pdo->exec("CREATE TABLE formularios (
            id SERIAL PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descripcion TEXT,
            campos JSONB NOT NULL,
            roles_permitidos JSONB NOT NULL,
            es_publico BOOLEAN DEFAULT FALSE,
            esta_activo BOOLEAN DEFAULT TRUE,
            fecha_limite DATE,
            created_by INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Form responses
        $pdo->exec("CREATE TABLE respuestas_formularios (
            id SERIAL PRIMARY KEY,
            formulario_id INT NOT NULL REFERENCES formularios(id) ON DELETE CASCADE,
            user_id INT REFERENCES users(id) ON DELETE SET NULL,
            respuestas JSONB NOT NULL,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Activity log
        $pdo->exec("CREATE TABLE actividad_log (
            id SERIAL PRIMARY KEY,
            user_id INT NOT NULL REFERENCES users(id) ON DELETE CASCADE,
            accion VARCHAR(100) NOT NULL,
            descripcion TEXT,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
        // Settings
        $pdo->exec("CREATE TABLE configuracion (
            id SERIAL PRIMARY KEY,
            clave VARCHAR(50) NOT NULL UNIQUE,
            valor TEXT,
            tipo VARCHAR(20) DEFAULT 'text' CHECK (tipo IN ('text', 'textarea', 'image', 'boolean', 'json')),
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )");
        
    } else {
        // MySQL schema
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
        
        $pdo->exec("DROP TABLE IF EXISTS users");
        $pdo->exec("CREATE TABLE users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            username VARCHAR(50) NOT NULL UNIQUE,
            email VARCHAR(100) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL,
            full_name VARCHAR(100) NOT NULL,
            phone VARCHAR(20),
            role ENUM('admin', 'supervisor', 'lider', 'admin_jovenes', 'admin_mujeres', 'admin_varones', 'admin_juveniles', 'admin_evangelismo', 'admin_intercesion', 'user') DEFAULT 'user',
            is_active BOOLEAN DEFAULT TRUE,
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS supervisor_lideres");
        $pdo->exec("CREATE TABLE supervisor_lideres (
            id INT AUTO_INCREMENT PRIMARY KEY,
            supervisor_id INT NOT NULL,
            lider_id INT NOT NULL,
            assigned_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE CASCADE,
            FOREIGN KEY (lider_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_assignment (supervisor_id, lider_id)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS miembros");
        $pdo->exec("CREATE TABLE miembros (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lider_id INT NOT NULL,
            nombre VARCHAR(100) NOT NULL,
            telefono VARCHAR(20),
            email VARCHAR(100),
            es_consolidacion BOOLEAN DEFAULT FALSE,
            fecha_bautizo DATE,
            esta_bautizado BOOLEAN DEFAULT FALSE,
            nota_consolidacion INT DEFAULT 0,
            is_active BOOLEAN DEFAULT TRUE,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lider_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS informes_semanales");
        $pdo->exec("CREATE TABLE informes_semanales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lider_id INT NOT NULL,
            semana_inicio DATE NOT NULL,
            semana_fin DATE NOT NULL,
            anfitrion VARCHAR(100),
            fecha_reunion DATE,
            direccion VARCHAR(255),
            hora_inicio TIME,
            hora_termino TIME,
            total_miembros INT DEFAULT 0,
            en_consolidacion INT DEFAULT 0,
            total_asistentes INT DEFAULT 0,
            nuevos_evangelizados INT DEFAULT 0,
            confesiones_fe INT DEFAULT 0,
            tema_expuesto VARCHAR(255),
            observaciones TEXT,
            ofrenda DECIMAL(10,2) DEFAULT 0,
            proxima_fecha_omt DATE,
            estado ENUM('borrador', 'completado') DEFAULT 'borrador',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (lider_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_report (lider_id, semana_inicio)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS asistencia_semanal");
        $pdo->exec("CREATE TABLE asistencia_semanal (
            id INT AUTO_INCREMENT PRIMARY KEY,
            informe_id INT NOT NULL,
            miembro_id INT NOT NULL,
            grupo_familiar ENUM('si', 'no', 'no_hubo') DEFAULT 'no',
            escuela ENUM('si', 'no', 'no_hubo') DEFAULT 'no',
            reunion_red ENUM('si', 'no', 'no_hubo') DEFAULT 'no',
            culto_domingo ENUM('si', 'no', 'no_hubo') DEFAULT 'no',
            actividad_omt ENUM('si', 'no', 'no_hubo') DEFAULT 'no',
            porcentaje_asistencia DECIMAL(5,2) DEFAULT 0,
            fecha_llamada_visita DATE,
            nota_consolidacion INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (informe_id) REFERENCES informes_semanales(id) ON DELETE CASCADE,
            FOREIGN KEY (miembro_id) REFERENCES miembros(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS estadisticas_mensuales");
        $pdo->exec("DROP TABLE IF EXISTS estadisticas_mensuales_supervisores");
        $pdo->exec("DROP TABLE IF EXISTS estadisticas_mensuales_admin");
        $pdo->exec("DROP TABLE IF EXISTS asistencia_supervisores");
        $pdo->exec("DROP TABLE IF EXISTS asistencia_admin");
        $pdo->exec("DROP TABLE IF EXISTS informes_supervisores");
        $pdo->exec("DROP TABLE IF EXISTS informes_admin");
        $pdo->exec("CREATE TABLE estadisticas_mensuales (
            id INT AUTO_INCREMENT PRIMARY KEY,
            lider_id INT NOT NULL,
            mes INT NOT NULL,
            anio INT NOT NULL,
            promedio_grupo_familiar DECIMAL(5,2) DEFAULT 0,
            promedio_escuela DECIMAL(5,2) DEFAULT 0,
            promedio_reunion_red DECIMAL(5,2) DEFAULT 0,
            promedio_culto_domingo DECIMAL(5,2) DEFAULT 0,
            promedio_actividad_omt DECIMAL(5,2) DEFAULT 0,
            promedio_general DECIMAL(5,2) DEFAULT 0,
            total_miembros INT DEFAULT 0,
            total_bautizados INT DEFAULT 0,
            total_consolidacion INT DEFAULT 0,
            archivo_pdf VARCHAR(255),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (lider_id) REFERENCES users(id) ON DELETE CASCADE,
            UNIQUE KEY unique_stats (lider_id, mes, anio)
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        // supervisor/admin tables for MySQL
        $pdo->exec("CREATE TABLE informes_supervisores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            supervisor_id INT NOT NULL,
            semana_inicio DATE NOT NULL,
            semana_fin DATE NOT NULL,
            anfitrion VARCHAR(255),
            fecha_reunion DATE,
            direccion VARCHAR(255),
            hora_inicio TIME,
            hora_termino TIME,
            total_miembros INT DEFAULT 0,
            total_asistentes INT DEFAULT 0,
            nuevos_evangelizados INT DEFAULT 0,
            confesiones_fe INT DEFAULT 0,
            tema_expuesto TEXT,
            observaciones TEXT,
            ofrenda DECIMAL(10,2) DEFAULT 0,
            proxima_fecha_omt DATE,
            estado ENUM('borrador','completado') DEFAULT 'borrador',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (supervisor_id, semana_inicio),
            FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("CREATE TABLE asistencia_supervisores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            informe_id INT NOT NULL,
            lider_id INT NOT NULL,
            grupo_familiar ENUM('si','no','no_hubo') DEFAULT 'no',
            escuela ENUM('si','no','no_hubo') DEFAULT 'no',
            reunion_red ENUM('si','no','no_hubo') DEFAULT 'no',
            culto_domingo ENUM('si','no','no_hubo') DEFAULT 'no',
            actividad_omt ENUM('si','no','no_hubo') DEFAULT 'no',
            porcentaje_asistencia DECIMAL(5,2) DEFAULT 0,
            fecha_llamada_visita DATE,
            nota_consolidacion INT DEFAULT 0,
            FOREIGN KEY (informe_id) REFERENCES informes_supervisores(id) ON DELETE CASCADE,
            FOREIGN KEY (lider_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("CREATE TABLE estadisticas_mensuales_supervisores (
            id INT AUTO_INCREMENT PRIMARY KEY,
            supervisor_id INT NOT NULL,
            mes INT NOT NULL,
            anio INT NOT NULL,
            promedio_grupo_familiar DECIMAL(5,2) DEFAULT 0,
            promedio_escuela DECIMAL(5,2) DEFAULT 0,
            promedio_reunion_red DECIMAL(5,2) DEFAULT 0,
            promedio_culto_domingo DECIMAL(5,2) DEFAULT 0,
            promedio_actividad_omt DECIMAL(5,2) DEFAULT 0,
            promedio_general DECIMAL(5,2) DEFAULT 0,
            total_miembros INT DEFAULT 0,
            UNIQUE KEY unique_sup (supervisor_id, mes, anio),
            FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("CREATE TABLE informes_admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            admin_id INT NOT NULL,
            semana_inicio DATE NOT NULL,
            semana_fin DATE NOT NULL,
            anfitrion VARCHAR(255),
            fecha_reunion DATE,
            direccion VARCHAR(255),
            hora_inicio TIME,
            hora_termino TIME,
            total_miembros INT DEFAULT 0,
            total_asistentes INT DEFAULT 0,
            nuevos_evangelizados INT DEFAULT 0,
            confesiones_fe INT DEFAULT 0,
            tema_expuesto TEXT,
            observaciones TEXT,
            ofrenda DECIMAL(10,2) DEFAULT 0,
            proxima_fecha_omt DATE,
            estado ENUM('borrador','completado') DEFAULT 'borrador',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            INDEX (admin_id, semana_inicio),
            FOREIGN KEY (admin_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("CREATE TABLE asistencia_admin (
            id INT AUTO_INCREMENT PRIMARY KEY,
            informe_id INT NOT NULL,
            supervisor_id INT NOT NULL,
            grupo_familiar ENUM('si','no','no_hubo') DEFAULT 'no',
            escuela ENUM('si','no','no_hubo') DEFAULT 'no',
            reunion_red ENUM('si','no','no_hubo') DEFAULT 'no',
            culto_domingo ENUM('si','no','no_hubo') DEFAULT 'no',
            actividad_omt ENUM('si','no','no_hubo') DEFAULT 'no',
            porcentaje_asistencia DECIMAL(5,2) DEFAULT 0,
            fecha_llamada_visita DATE,
            nota_consolidacion INT DEFAULT 0,
            FOREIGN KEY (informe_id) REFERENCES informes_admin(id) ON DELETE CASCADE,
            FOREIGN KEY (supervisor_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS publicaciones");
        $pdo->exec("CREATE TABLE publicaciones (
            id INT AUTO_INCREMENT PRIMARY KEY,
            seccion ENUM('jovenes', 'mujeres', 'varones', 'juveniles', 'evangelismo', 'intercesion') NOT NULL,
            autor_id INT NOT NULL,
            titulo VARCHAR(255) NOT NULL,
            slug VARCHAR(255),
            contenido TEXT NOT NULL,
            imagen VARCHAR(255),
            video_url VARCHAR(255),
            es_destacado BOOLEAN DEFAULT FALSE,
            estado ENUM('borrador', 'publicado') DEFAULT 'borrador',
            vistas INT DEFAULT 0,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (autor_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS intercesion");
        $pdo->exec("CREATE TABLE intercesion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            nombre VARCHAR(100) NOT NULL,
            email VARCHAR(100),
            peticion TEXT NOT NULL,
            es_privada BOOLEAN DEFAULT FALSE,
            estado ENUM('pendiente', 'aprobada', 'rechazada') DEFAULT 'pendiente',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS formularios");
        $pdo->exec("CREATE TABLE formularios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            titulo VARCHAR(255) NOT NULL,
            descripcion TEXT,
            campos JSON NOT NULL,
            roles_permitidos JSON NOT NULL,
            es_publico BOOLEAN DEFAULT FALSE,
            esta_activo BOOLEAN DEFAULT TRUE,
            fecha_limite DATE,
            created_by INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (created_by) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS respuestas_formularios");
        $pdo->exec("CREATE TABLE respuestas_formularios (
            id INT AUTO_INCREMENT PRIMARY KEY,
            formulario_id INT NOT NULL,
            user_id INT,
            respuestas JSON NOT NULL,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (formulario_id) REFERENCES formularios(id) ON DELETE CASCADE,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS actividad_log");
        $pdo->exec("CREATE TABLE actividad_log (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT NOT NULL,
            accion VARCHAR(100) NOT NULL,
            descripcion TEXT,
            ip_address VARCHAR(45),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("DROP TABLE IF EXISTS configuracion");
        $pdo->exec("CREATE TABLE configuracion (
            id INT AUTO_INCREMENT PRIMARY KEY,
            clave VARCHAR(50) NOT NULL UNIQUE,
            valor TEXT,
            tipo ENUM('text', 'textarea', 'image', 'boolean', 'json') DEFAULT 'text',
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
        
        $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    }
    
    // Insert default admin user (password: admin123)
    $adminPassword = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("INSERT INTO users (username, email, password, full_name, role) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute(['admin', 'admin@mca.org', $adminPassword, 'Administrador General', 'admin']);
    
    // Insert default settings
    $settings = [
        ['site_name', 'MCA - Movilización Cristiana', 'text'],
        ['site_description', 'Movilizando a la iglesia para transformar vidas', 'textarea'],
        ['site_email', 'contacto@mca.org', 'text'],
        ['site_phone', '+56 9 1234 5678', 'text'],
        ['site_address', 'Santiago, Chile', 'text'],
        ['facebook_url', 'https://facebook.com/mca', 'text'],
        ['instagram_url', 'https://www.instagram.com/iglesiamovilizacioncristiana', 'text'],
        ['youtube_url', 'https://youtube.com/mca', 'text'],
    ];
    
    $stmt = $pdo->prepare("INSERT INTO configuracion (clave, valor, tipo) VALUES (?, ?, ?)");
    foreach ($settings as $setting) {
        $stmt->execute($setting);
    }
    
    echo "\n✅ Base de datos instalada correctamente!\n";
    echo "📧 Usuario admin: admin\n";
    echo "🔑 Contraseña: admin123\n";
    echo "⚠️ Cambia la contraseña después de iniciar sesión.\n";
    
} catch (PDOException $e) {
    die("Error: " . $e->getMessage() . "\n");
}

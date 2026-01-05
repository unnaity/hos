BEGIN
SELECT rm.raw_material_id,
		rm.raw_material_name,
        rm.raw_material_code,
        rm.min_level,
        rm.category_id,
        rm.size,
        rm.hsn_code,
        rm.store_id,
        rm.max_level,
        rm.inward_unit,
        rm.outward_unit,
        rm.wastage,
        rm.additional_info,
        c.name category_name,
        c.hsn_code,
        sz.size_id,
        sz.size
	FROM tbl_raw_material rm
    LEFT JOIN tbl_category c ON c.category_id = rm.category_id
	LEFT JOIN tbl_size sz ON sz.size_id = rm.size	
	WHERE 	rm.is_deleted = '0'
			AND (rm.raw_material_id = RAW_MATERIAL_ID OR RAW_MATERIAL_ID = 0)
			AND (rm.raw_material_name LIKE CONCAT('%',RAW_MATERIAL_NAME,'%') OR RAW_MATERIAL_NAME IS NULL)
			AND (rm.category_id LIKE CONCAT('%',CATEGORY_ID,'%') OR CATEGORY_ID = 0)
            AND rm.store_id = STORE_ID;
END
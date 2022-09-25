import React from 'react'

function TableHead({ basePrices }) {
    return (
        <thead>
            <tr>
                <th></th>
                <th>Логістика</th>
                {basePrices.map(el => 
                    <th key={el.id}>{el.title}</th>
                )}
            </tr>
        </thead>
    )    
}

export default TableHead
import React from 'react'
import TableRowBase from './TableRowBase.jsx'

function TableHead({ basePrices, changeBasePrices }) {
    return (
        <thead>
            <tr>
                <th></th>
                <th>Логістика</th>
                {basePrices.map(el => 
                    <th key={el.id}>{el.title}</th>
                )}
            </tr>
            <TableRowBase basePrices={basePrices} changeBasePrices={changeBasePrices} />
        </thead>
    )    
}

export default TableHead
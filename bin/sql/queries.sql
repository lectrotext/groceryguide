## general listing for search
select types.name, produce.commodity, produce.variety, produce.size, (produce.plu + types.plu) as plu, 
amounts.amount as number, amounts.unit, items.price, stores.name, stores.address, items.deal_start, items.deal_end,
reports.date as report_date, users.handle
from items 
left join types on types.id = items.t_id
left join produce on produce.id = items.p_id
left join amounts on amounts.id = items.a_id 
left join stores on stores.id = items.s_id
left join reports on reports.id = items.r_id
left join users on users.id = reports.u_id;  

## amounts inner join
select a1.amount, a2.unit, a2.abbreviation, a1.unit as container, a1.symbol from amounts as a1
inner join amounts a2 on a1.amount_u_id = a2.id;


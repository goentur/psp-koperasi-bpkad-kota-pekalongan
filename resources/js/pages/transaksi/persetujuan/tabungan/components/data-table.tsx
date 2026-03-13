import LoadingData from '@/components/data-table/loading-data'
import NoData from '@/components/data-table/no-data'
import { Badge } from '@/components/ui/badge'
import { Button } from '@/components/ui/button'
import { CheckCircle } from 'lucide-react'

type DataTableProps = {
    loading: boolean
    data: []
    from: number
    setData: React.Dispatch<React.SetStateAction<any>>
    setAksiTerima: React.Dispatch<React.SetStateAction<boolean>>
}

export default function DataTable({ loading, data, from, setData, setAksiTerima }: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead>
                <tr className="uppercase text-sm leading-normal">
                    <th className="p-2 border w-1">NO</th>
                    <th className="p-2 border">Anggota</th>
                    <th className="p-2 border">Tabungan</th>
                    <th className="p-2 border w-1">Tipe</th>
                    <th className="p-2 border w-1">Tanggal</th>
                    <th className="p-2 border w-1">Nominal</th>
                    <th className="p-2 border w-1">Aksi</th>
                </tr>
            </thead>
            <tbody className="font-light">
                {loading && <LoadingData colSpan={7}/>}
                {data.length > 0 ? (
                    data.map((value: any, index: number) => (
                        <tr
                            key={index}
                            className="hover:bg-gray-100 dark:hover:bg-slate-900 align-text-top"
                        >
                            <td className="px-2 py-1 border text-center">{from++}</td>
                            <td className="px-2 py-1 border">{value?.anggota}</td>
                            <td className="px-2 py-1 border">{value?.tabungan}</td>
                            <td className="px-2 py-1 border text-center">{value?.tipe == '01' ? <><Badge className="bg-green-50 text-green-700 dark:bg-green-950 dark:text-green-300">SETOR</Badge></>:<><Badge className="bg-red-50 text-red-700 dark:bg-red-950 dark:text-red-300">TARIK</Badge></>}</td>
                            <td className="px-2 py-1 border whitespace-nowrap">{value?.tgl_trans}</td>
                            <td className="px-2 py-1 border text-end">{value?.nominal}</td>
                            <td className="px-2 py-1 border"><Button variant={'destructive'} size={'sm'} onClick={() => {setAksiTerima(true),setData({id: value.id})}}><CheckCircle data-icon="inline-start" /></Button></td>
                        </tr>
                    ))
                ) : (!loading ?<NoData colSpan={7}/>: null)}
            </tbody>
        </table>
    )
}

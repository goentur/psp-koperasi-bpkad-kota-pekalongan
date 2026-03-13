import LoadingData from '@/components/data-table/loading-data'
import NoData from '@/components/data-table/no-data'

type DataTableProps = {
    loading: boolean
    data: []
    from: number
    setForm: React.Dispatch<React.SetStateAction<boolean>>
    setIsEdit: React.Dispatch<React.SetStateAction<boolean>>
    setData: React.Dispatch<React.SetStateAction<any>>
    setHapus: React.Dispatch<React.SetStateAction<boolean>>
}

export default function DataTable({ loading, data, from, setForm, setIsEdit, setData, setHapus }: DataTableProps) {
    return (
        <table className="w-full text-left border-collapse border">
            <thead>
                <tr className="uppercase text-sm leading-normal">
                    <th className="p-2 border w-1">NO</th>
                    <th className="p-2 border">Anggota</th>
                    <th className="p-2 border">Tabungan</th>
                    <th className="p-2 border">Status Kepegawaian</th>
                    <th className="p-2 border">Satuan Kerja</th>
                    <th className="p-2 border w-1">Aksi</th>
                </tr>
            </thead>
            <tbody className="font-light">
                {loading && <LoadingData colSpan={5}/>}
                {data.length > 0 ? (
                    data.map((value: any, index: number) => (
                        <tr
                            key={index}
                            className="hover:bg-gray-100 dark:hover:bg-slate-900 align-text-top"
                        >
                            <td className="px-2 py-1 border text-center">{from++}</td>
                            <td className="px-2 py-1 w-1 border">{value?.nik}</td>
                            <td className="px-2 py-1 border">{value?.nama}</td>
                            <td className="px-2 py-1 border">{value?.status_kepegawaian}</td>
                            <td className="px-2 py-1 border">{value?.satuan_kerja?.nama}</td>
                            <td className="border text-center">
                            </td>
                        </tr>
                    ))
                ) : (!loading ?<NoData colSpan={5}/>: null)}
            </tbody>
        </table>
    )
}

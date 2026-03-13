import DataTablePagination from '@/components/data-table/pagination'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, InfoDataTabel } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'
import Filters from './components/filters'
import Terima from './components/terima'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: 'dashboard',
    },
    {
        title: 'Transaksi',
        href: 'transaksi.persetujuanSimpanan',
    },
    {
        title: 'Persetujuan',
        href: 'transaksi.persetujuanSimpanan',
    },
];

export default function Index({simpanans}:any) {
    const title = 'Persetujuan Simpanan'
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [loading, setLoading] = useState(false)
    const [dataTable, setDataTable] = useState<[]>([])
    const [linksPagination, setLinksPagination] = useState([])
    const [aksiTerima, setAksiTerima] = useState(false)
    const [infoDataTabel, setInfoDataTabel] = useState<any>({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        anggota : null,
        simpanan : null,
    })

    const { data, setData, post, processing } = useForm({
        id : null,
    })

    useEffect(() => {
        getData()
    }, [infoDataTabel.page, infoDataTabel.search, infoDataTabel.perPage, infoDataTabel.anggota, infoDataTabel.simpanan])

    const getData = async () => {
        setLoading(true)
        try {
            const response = await axios.post(route('transaksi.dataTransaksiSimpanan'), {
                page: infoDataTabel.page,
                anggota: infoDataTabel.anggota,
                simpanan: infoDataTabel.simpanan,
                perPage: infoDataTabel.perPage,
            })
            setDataTable(response.data.data)
            setLinksPagination(response.data.links)
            setInfoDataTabel((prev:any) => ({
                ...prev,
                page: response.data.current_page,
                from: response.data.from,
                to: response.data.to,
                total: response.data.total,
                perPage: response.data.per_page,
            }))
        } catch (error: any) {
            alertApp(error.message, 'error')
        } finally {
            setLoading(false)
        }
    }
    const handleHapus = (e: React.FormEvent) => {
        e.preventDefault()
        post(route('transaksi.terimaTransaksiSimpanan', {id:data.id}), {
            preserveScroll: true,
            onSuccess: (e) => {setAksiTerima(false),alertApp(e),getData()},
            onError: (e) => {alertApp(e.message, 'error')},
        })
    }
    return (
        <AppLayout breadcrumbs={breadcrumbs}>
            <Head title={title} />
            <div className="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
                <Card>
                    <CardHeader>
                        <CardTitle className="text-xl">{title}</CardTitle>
                    </CardHeader>
                    <CardContent>
                        <Filters
                            data={infoDataTabel}
                            setInfoDataTabel={setInfoDataTabel}
                            simpanans={simpanans}
                        />
                        <DataTable
                            loading={loading}
                            data={dataTable}
                            from={infoDataTabel.from}
                            setData={setData}
                            setAksiTerima={setAksiTerima}
                        />
                        <DataTablePagination
                            infoDataTabel={infoDataTabel}
                            setInfoDataTabel={setInfoDataTabel}
                            linksPagination={linksPagination}
                        />
                    </CardContent>
                </Card>
            </div>
            <Terima
                open={aksiTerima}
                setOpen={setAksiTerima}
                processing={processing}
                handleHapusData={handleHapus}
            />
        </AppLayout>
    )
}

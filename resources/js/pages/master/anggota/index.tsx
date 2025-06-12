import Delete from '@/components/data-table/delete'
import DataTableFilters from '@/components/data-table/filters'
import DataTablePagination from '@/components/data-table/pagination'
import { Card, CardContent, CardHeader, CardTitle } from '@/components/ui/card'
import { alertApp } from '@/components/utils'
import AppLayout from '@/layouts/app-layout'
import { BreadcrumbItem, IndexGate, InfoDataTabel } from '@/types'
import { Head, useForm } from '@inertiajs/react'
import axios from 'axios'
import { useEffect, useRef, useState } from 'react'
import DataTable from './components/data-table'
import FormDialog from './components/form-dialog'

const breadcrumbs: BreadcrumbItem[] = [
    {
        title: 'Dashboard',
        href: 'dashboard',
    },
    {
        title: 'Master',
        href: 'anggota.index',
    },
    {
        title: 'Anggota',
        href: 'anggota.index',
    },
];

export default function Index({ gate }: IndexGate) {
    const title = 'Anggota'
    const [form, setForm] = useState(false)
    const [hapus, setHapus] = useState(false)
    const formRefs = useRef<Record<string, HTMLInputElement | null>>({})
    const [loading, setLoading] = useState(false)
    const [isEdit, setIsEdit] = useState(false)
    const [dataTable, setDataTable] = useState<[]>([])
    const [linksPagination, setLinksPagination] = useState([])
    const [infoDataTabel, setInfoDataTabel] = useState<InfoDataTabel>({
        page: 1,
        from: 0,
        to: 0,
        total: 0,
        perPage: 25,
        search: null,
    })

    const { data, setData, errors, post, patch, delete: destroy, processing } = useForm()

    useEffect(() => {
        getData()
    }, [infoDataTabel.page, infoDataTabel.search, infoDataTabel.perPage])

    const getData = async () => {
        setLoading(true)
        try {
            const response = await axios.post(route('anggota.data'), {
                page: infoDataTabel.page,
                search: infoDataTabel.search,
                perPage: infoDataTabel.perPage,
            })
            setDataTable(response.data.data)
            setLinksPagination(response.data.links)
            setInfoDataTabel((prev) => ({
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
    const reset = () => {
        setData({
            id: "",
            nik: "",
            nama: "",
            statusKepegawaian: "",
            jenisTabungan: "",
            tanggal: "",
            nominal: "",
            satuanKerja: "",
        })
    }
    const handleForm = (e: React.FormEvent) => {
        e.preventDefault()
        const action = isEdit ? patch : post
        const routeName = isEdit ? route('anggota.update', data).toString() : route('anggota.store').toString()
        action(routeName, {
            preserveScroll: true,
            onSuccess: (e) => {reset(), setForm(false), alertApp(e), getData()},
            onError: (e) => {
                const firstErrorKey = Object.keys(e)[0]
                if (firstErrorKey) {
                    formRefs.current[firstErrorKey]?.focus()
                }
            },
        })
    }
    const handleHapus = (e: React.FormEvent) => {
        e.preventDefault()
        destroy(route('anggota.destroy', data), {
            preserveScroll: true,
            onSuccess: (e) => {setHapus(false),alertApp(e),getData()},
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
                        <DataTableFilters
                            gate={gate}
                            setInfoDataTabel={setInfoDataTabel}
                            onClick={() => {reset(), setIsEdit(false), setForm(true)}}
                        />
                        <DataTable
                            gate={gate}
                            loading={loading}
                            data={dataTable}
                            from={infoDataTabel.from}
                            setForm={setForm}
                            setIsEdit={setIsEdit}
                            setData={setData}
                            setHapus={setHapus}
                        />
                        <DataTablePagination
                            infoDataTabel={infoDataTabel}
                            setInfoDataTabel={setInfoDataTabel}
                            linksPagination={linksPagination}
                        />
                    </CardContent>
                </Card>
            </div>
            <FormDialog
                open={form}
                setOpen={setForm}
                title={title}
                data={data}
                setData={setData}
                errors={errors}
                formRefs={formRefs}
                processing={processing}
                handleForm={handleForm}
                isEdit={isEdit}
            />
            <Delete
                open={hapus}
                setOpen={setHapus}
                processing={processing}
                handleHapusData={handleHapus}
            />
        </AppLayout>
    )
}

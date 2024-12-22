export enum AppType {
  UNSPECIFIED = "UNSPECIFIED",
  WEB_APP = "WEB_APP",
  MOBILE_APP = "MOBILE_APP",
  DESKTOP_APP = "DESKTOP_APP",
  CLI_TOOL = "CLI_TOOL",
  GAME = "GAME",
  OTHER = "OTHER"
}

export const AppTypeLabels: Record<AppType, string> = {
  [AppType.UNSPECIFIED]: "未選択",
  [AppType.WEB_APP]: "Webアプリ",
  [AppType.MOBILE_APP]: "モバイルアプリ",
  [AppType.DESKTOP_APP]: "デスクトップアプリ",
  [AppType.CLI_TOOL]: "CLIツール",
  [AppType.GAME]: "ゲーム",
  [AppType.OTHER]: "その他"
}

export const AppTypeColors: Record<AppType, string> = {
  [AppType.UNSPECIFIED]: "gray",
  [AppType.WEB_APP]: "blue",
  [AppType.MOBILE_APP]: "green",
  [AppType.DESKTOP_APP]: "purple",
  [AppType.CLI_TOOL]: "orange",
  [AppType.GAME]: "red",
  [AppType.OTHER]: "gray"
}

export interface App {
  _id: string
  title: string
  description: string
  app_type: AppType
  category?: string
  demo_url?: string
  github_url?: string
  screenshots: string[]
  created_at: string
  user?: {
    _id: string
    username: string
    display_name?: string
  }
} 
